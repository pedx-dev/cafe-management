<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Courier\GoMetrixSyncService;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.menuItem'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(20);

        $statusCounts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.menuItem'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        try {
            app(TwilioService::class)->sendOrderStatus($order->user, $order, ucfirst($validated['status']));
        } catch (\Throwable $e) {
            Log::warning('Failed to send admin status SMS', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }

        if ($order->courier_provider === 'gometrix') {
            $syncResult = app(GoMetrixSyncService::class)->syncFromCafeStatus($order, $validated['status']);

            if (! (bool) ($syncResult['success'] ?? false)) {
                Log::warning('Cafe to GoMetrix status sync failed', [
                    'order_id' => $order->id,
                    'status' => $validated['status'],
                    'message' => $syncResult['message'] ?? 'Unknown sync error',
                ]);

                return redirect()->back()->with('warning', 'Order status updated in Cafe, but GoMetrix sync failed: ' . ($syncResult['message'] ?? 'Unknown sync error.'));
            }

            $remoteStatus = $syncResult['data']['status'] ?? null;
            $remoteReference = $syncResult['data']['reference'] ?? null;

            if ($remoteStatus || $remoteReference) {
                $order->update([
                    'courier_status' => $remoteStatus ?? $order->courier_status,
                    'courier_reference' => $remoteReference ?? $order->courier_reference,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Update rider tracking location and ETA.
     */
    public function updateTracking(Request $request, $id)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'eta' => 'nullable|string|max:100',
        ]);

        $order = Order::findOrFail($id);

        $order->orderTracking()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'status'  => $order->status,
                'lat'     => $validated['lat'],
                'lng'     => $validated['lng'],
                'eta'     => $validated['eta'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Rider location updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
