<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\TwilioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
    
class StripeWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $secret = (string) config('services.stripe.webhook_secret');
        if ($secret === '') {
            return response()->json(['message' => 'Webhook secret is not configured.'], 500);
        }

        $payload = $request->getContent();
        $signature = (string) $request->header('Stripe-Signature', '');

        if (! $this->isValidSignature($payload, $signature, $secret)) {
            return response()->json(['message' => 'Invalid signature.'], 400);
        }

        $event = json_decode($payload, true);
        if (! is_array($event)) {
            return response()->json(['message' => 'Invalid payload.'], 400);
        }

        $eventType = (string) Arr::get($event, 'type', '');

        if ($eventType === 'checkout.session.completed') {
            $this->handleCheckoutCompleted((array) Arr::get($event, 'data.object', []));
        }

        return response()->json(['received' => true]);
    }

    private function handleCheckoutCompleted(array $session): void
    {
        $sessionId = (string) Arr::get($session, 'id', '');
        $orderId = Arr::get($session, 'metadata.order_id')
            ?? Arr::get($session, 'client_reference_id');

        if (! $orderId && $sessionId !== '') {
            $orderId = Order::where('stripe_session_id', $sessionId)->value('id');
        }

        if (! $orderId) {
            Log::warning('Stripe webhook: order could not be resolved', ['session_id' => $sessionId]);
            return;
        }

        $order = Order::with('user')->find($orderId);
        if (! $order) {
            Log::warning('Stripe webhook: order not found', ['order_id' => $orderId, 'session_id' => $sessionId]);
            return;
        }

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'stripe_session_id' => $sessionId ?: $order->stripe_session_id,
                'stripe_payment_intent_id' => Arr::get($session, 'payment_intent', $order->stripe_payment_intent_id),
            ]);

            try {
                app(TwilioService::class)->sendOrderStatus($order->user, $order, 'Confirmed');
            } catch (\Throwable $e) {
                Log::warning('Stripe webhook SMS send failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function isValidSignature(string $payload, string $header, string $secret): bool
    {
        if ($header === '') {
            return false;
        }

        $parts = [];
        foreach (explode(',', $header) as $item) {
            [$key, $value] = array_pad(explode('=', trim($item), 2), 2, null);
            if ($key && $value) {
                $parts[$key] = $value;
            }
        }

        $timestamp = $parts['t'] ?? null;
        $signature = $parts['v1'] ?? null;

        if (! $timestamp || ! $signature) {
            return false;
        }

        $signedPayload = $timestamp . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expected, $signature);
    }
}
