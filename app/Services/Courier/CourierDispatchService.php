<?php

namespace App\Services\Courier;

use App\Models\Order;

class CourierDispatchService
{
    public function __construct(private readonly CourierProviderManager $providerManager)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function dispatchOrder(Order $order): array
    {
        $order->loadMissing(['user', 'items', 'orderTracking']);

        $providerKey = match ($order->delivery_type) {
            'fasttrack' => 'fasttrack',
            'gometrix' => 'gometrix',
            default => (string) config('services.courier_integration.default_provider', 'fasttrack'),
        };

        $payload = $this->buildCanonicalPayload($order);
        $result = $this->providerManager->sendOrder($providerKey, $payload);

        if ((bool) ($result['success'] ?? false)) {
            $resultData = $result['data'] ?? [];

            $order->update([
                'courier_provider' => $providerKey,
                'courier_reference' => $resultData['reference'] ?? $resultData['delivery_order_id'] ?? $order->courier_reference,
                'courier_status' => $resultData['status'] ?? $order->courier_status,
            ]);
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function buildCanonicalPayload(Order $order): array
    {
        return [
            'source_system' => 'cafe-management',
            'source_order_id' => $order->id,
            'order_number' => $order->order_number,
            'delivery_type' => $order->delivery_type,
            'customer' => [
                'name' => $order->user?->name,
                'phone' => $order->user?->phone,
                'email' => $order->user?->email,
            ],
            'delivery' => [
                'address' => $order->delivery_address,
                'lat' => $order->orderTracking?->lat,
                'lng' => $order->orderTracking?->lng,
                'eta_minutes' => $order->orderTracking?->eta,
            ],
            'payment' => [
                'method' => $order->payment_method,
                'status' => $order->payment_status,
                'amount' => (float) $order->total_amount,
                'currency' => strtoupper((string) config('services.stripe.currency', 'php')),
                // Demo redirect URLs: GoMetrix can send the Xendit payer back to Cafe.
                'success_redirect_url' => route('orders.show', ['id' => $order->id]),
                'failure_redirect_url' => route('orders.show', ['id' => $order->id]),
            ],
            'items' => $order->items->map(fn ($item) => [
                'menu_item_id' => $item->menu_item_id,
                'name' => $item->item_name,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->price,
                'line_total' => (float) ($item->price * $item->quantity),
            ])->values()->all(),
            'notes' => $order->notes,
            'placed_at' => optional($order->created_at)->toIso8601String(),
        ];
    }
}
