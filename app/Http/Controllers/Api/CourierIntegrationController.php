<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FastTrackStatusUpdateRequest;
use App\Http\Requests\Api\GoMetrixStatusUpdateRequest;
use App\Http\Requests\Api\SendOrderRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Order;
use App\Services\Courier\CourierDispatchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourierIntegrationController extends Controller
{
    use ApiResponse;

    public function sendOrder(SendOrderRequest $request, CourierDispatchService $dispatchService): JsonResponse
    {
        $validated = $request->validated();

        $order = Order::query()
            ->when(! empty($validated['order_id']), fn ($query) => $query->where('id', $validated['order_id']))
            ->when(! empty($validated['order_code']), fn ($query) => $query->orWhere('order_code', $validated['order_code']))
            ->firstOrFail();

        if (! in_array($order->delivery_type, ['fasttrack', 'gometrix'], true)) {
            return $this->respondError('Only fasttrack or gometrix delivery orders can be sent using this endpoint.', [
                'order_id' => $order->id,
                'delivery_type' => $order->delivery_type,
            ], [], 422);
        }

        $expectedProvider = $order->delivery_type === 'gometrix' ? 'gometrix' : 'fasttrack';
        $authFailure = $this->authorizeIntegrationRequest($request, $expectedProvider);
        if ($authFailure) {
            return $authFailure;
        }

        $result = $dispatchService->dispatchOrder($order);

        return (bool) ($result['success'] ?? false)
            ? $this->respondSuccess((string) ($result['message'] ?? 'Dispatch completed.'), $result['data'] ?? [], 200)
            : $this->respondError((string) ($result['message'] ?? 'Dispatch failed.'), $result['data'] ?? [], $result['errors'] ?? [], 502);
    }

    public function statusUpdate(FastTrackStatusUpdateRequest $request): JsonResponse
    {
        $authFailure = $this->authorizeIntegrationRequest($request, 'fasttrack');
        if ($authFailure) {
            return $authFailure;
        }

        $validated = $request->validated();

        $order = Order::findOrFail($validated['source_order_id']);

        $mappedStatus = $this->mapFastTrackStatusToOrderStatus($validated['status']);

        $order->update([
            'status' => $mappedStatus ?? $order->status,
            'courier_provider' => 'fasttrack',
            'courier_reference' => $validated['reference'] ?? $validated['delivery_order_id'] ?? $order->courier_reference,
            'courier_status' => $validated['status'],
        ]);

        return $this->respondSuccess('Order status updated', [
            'order_id' => $order->id,
            'status' => $order->status,
            'courier_status' => $order->courier_status,
            'courier_reference' => $order->courier_reference,
        ]);
    }

    public function goMetrixStatusUpdate(GoMetrixStatusUpdateRequest $request): JsonResponse
    {
        $authFailure = $this->authorizeIntegrationRequest($request, 'gometrix');
        if ($authFailure) {
            return $authFailure;
        }

        $validated = $request->validated();
        $order = Order::findOrFail($validated['source_order_id']);

        $mappedStatus = $this->mapGoMetrixStatusToOrderStatus($validated['status']);

        $order->update([
            'status' => $mappedStatus ?? $order->status,
            'courier_provider' => 'gometrix',
            'courier_reference' => (string) ($validated['reference'] ?? $validated['delivery_order_id'] ?? $order->courier_reference),
            'courier_status' => $validated['status'],
            'payment_status' => $validated['payment_status'] ?? $order->payment_status,
        ]);

        return $this->respondSuccess('GoMetrix status synced to cafe order', [
            'order_id' => $order->id,
            'status' => $order->status,
            'courier_status' => $order->courier_status,
            'courier_reference' => $order->courier_reference,
        ]);
    }

    private function authorizeIntegrationRequest(Request $request, string $providerKey): ?JsonResponse
    {
        $headerName = (string) config('services.courier_integration.api_key_header', 'X-Integration-Key');
        $configuredApiKey = (string) config("services.courier_integration.{$providerKey}.api_key", '');
        $incomingApiKey = (string) $request->header($headerName, '');

        if ($configuredApiKey === '' || ! hash_equals($configuredApiKey, $incomingApiKey)) {
            return $this->respondError('Invalid integration key.', [], [], 401);
        }

        return null;
    }

    private function mapGoMetrixStatusToOrderStatus(string $goMetrixStatus): ?string
    {
        return match ($goMetrixStatus) {
            'pending' => 'pending',
            'accepted' => 'confirmed',
            'picked_up' => 'preparing',
            'in_transit' => 'ready',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            default => null,
        };
    }

    private function mapFastTrackStatusToOrderStatus(string $fastTrackStatus): ?string
    {
        return match ($fastTrackStatus) {
            'accepted' => 'confirmed',
            'arriving_at_pickup',
            'at_pickup',
            'picked_up' => 'preparing',
            'arriving_at_dropoff',
            'at_dropoff',
            'in_transit' => 'ready',
            'delivered' => 'delivered',
            'cancelled',
            'cancelled_by_user',
            'cancelled_by_courier',
            'cancelled_by_system',
            'delivery_failed',
            'expired',
            'returned' => 'cancelled',
            default => null,
        };
    }
}
