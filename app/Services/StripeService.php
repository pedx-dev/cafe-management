<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class StripeService
{
    public function isConfigured(): bool
    {
        return filled(config('services.stripe.secret_key'));
    }

    /**
     * @return array{id:string,url:string}
     */
    public function createCheckoutSession(Order $order, string $successUrl, string $cancelUrl): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Stripe API keys are not configured.');
        }

        $response = Http::asForm()
            ->withToken((string) config('services.stripe.secret_key'))
            ->post(rtrim((string) config('services.stripe.base_url'), '/') . '/checkout/sessions', [
                'mode' => 'payment',
                'success_url' => $successUrl . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $cancelUrl,
                'client_reference_id' => (string) $order->id,
                'metadata[order_id]' => (string) $order->id,
                'line_items[0][price_data][currency]' => strtolower((string) config('services.stripe.currency', 'php')),
                'line_items[0][price_data][product_data][name]' => 'Cafe Order ' . ($order->order_code ?? ('#' . $order->id)),
                'line_items[0][price_data][unit_amount]' => (int) round(((float) $order->total_amount) * 100),
                'line_items[0][quantity]' => 1,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Failed to create Stripe checkout session.');
        }

        $payload = $response->json();
        $id = Arr::get($payload, 'id');
        $url = Arr::get($payload, 'url');

        if (! $id || ! $url) {
            throw new RuntimeException('Stripe checkout response is missing required fields.');
        }

        return ['id' => $id, 'url' => $url];
    }

    public function retrieveCheckoutSession(string $sessionId): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $response = Http::withToken((string) config('services.stripe.secret_key'))
            ->get(rtrim((string) config('services.stripe.base_url'), '/') . '/checkout/sessions/' . $sessionId);

        if (! $response->successful()) {
            return null;
        }

        return $response->json();
    }
}
