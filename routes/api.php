<?php

use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Http\Controllers\Api\CourierIntegrationController;
use App\Http\Controllers\Api\OrderTrackingController;
use App\Http\Controllers\Api\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/orders/{order_code}', function ($order_code) {
    $order = Order::with(['items.menuItem', 'user'])
        ->where('order_code', $order_code)
        ->first();

    if (!$order) {
        return response()->json(['success' => false, 'message' => 'Order not found'], 404);
    }

    return response()->json([
        'success' => true,
        'order' => [
            'id' => $order->id,
            'order_code' => $order->order_code,
            'status' => $order->status,
            'items' => $order->items->map(function ($item) {
                return [
                    'name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ];
            }),
            'total' => $order->total_amount,
            'customer' => [
                'name' => $order->user->name,
            ],
            'created_at' => $order->created_at->toDateTimeString(),
        ]
    ]);
});

Route::get('/orders/{order_id}/tracking', [OrderTrackingController::class, 'show']);

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('api.stripe.webhook');

Route::post('/send-order', [CourierIntegrationController::class, 'sendOrder'])
    ->middleware('throttle:integration-send-order')
    ->name('api.integration.send-order');

Route::post('/fasttrack/status-update', [CourierIntegrationController::class, 'statusUpdate'])
    ->middleware('throttle:integration-callback')
    ->name('api.integration.fasttrack.status-update');

Route::post('/gometrix/status-update', [CourierIntegrationController::class, 'goMetrixStatusUpdate'])
    ->middleware('throttle:integration-callback')
    ->name('api.integration.gometrix.status-update');
