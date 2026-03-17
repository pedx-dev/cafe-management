<?php

namespace App\Services\Courier;

use App\Services\Courier\Contracts\CourierProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoMetrixCourierProvider implements CourierProviderInterface
{
    public function providerKey(): string
    {
        return 'gometrix';
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function sendOrder(array $payload): array
    {
        $config = config('services.courier_integration');
        $providerConfig = $config['gometrix'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | DEMO / LOCALHOST CONFIG NOTES
        |--------------------------------------------------------------------------
        | 1) GO_METRIX_BASE_URL should point to the GoMetrix app URL.
        |    Example local: http://127.0.0.1:8001
        |    Example LAN demo: http://192.168.1.50:8001
        | 2) GO_METRIX_OUTBOUND_ORDER_PATH is the API path in GoMetrix that accepts
        |    imported cafe orders.
        | 3) Keep header/key values in .env only for demo safety.
        */
        $url = rtrim((string) ($providerConfig['base_url'] ?? ''), '/') . '/' . ltrim((string) ($providerConfig['outbound_order_path'] ?? '/api/integrations/cafe/orders'), '/');
        $headerName = (string) ($config['api_key_header'] ?? 'X-Integration-Key');
        $apiKey = (string) ($providerConfig['api_key'] ?? '');

        try {
            $response = Http::acceptJson()
                ->asJson()
                ->withHeaders([$headerName => $apiKey])
                ->connectTimeout((int) ($config['connect_timeout_seconds'] ?? 5))
                ->timeout((int) ($config['request_timeout_seconds'] ?? 10))
                ->retry((int) ($config['max_retries'] ?? 1), (int) ($config['retry_sleep_milliseconds'] ?? 200))
                ->post($url, $payload);

            $body = $response->json() ?: [];

            Log::info('GoMetrix order dispatch response', [
                'url' => $url,
                'http_status' => $response->status(),
                'order_id' => $payload['source_order_id'] ?? null,
                'success' => (bool) ($body['success'] ?? false),
            ]);

            if ($response->successful() && (bool) ($body['success'] ?? false)) {
                return [
                    'success' => true,
                    'message' => (string) ($body['message'] ?? 'Order sent to GoMetrix.'),
                    'data' => $body['data'] ?? [],
                    'errors' => $body['errors'] ?? [],
                    'http_status' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'message' => (string) ($body['message'] ?? 'GoMetrix rejected the request.'),
                'data' => $body['data'] ?? [],
                'errors' => $body['errors'] ?? [],
                'http_status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            Log::error('GoMetrix order dispatch failed', [
                'url' => $url,
                'order_id' => $payload['source_order_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'GoMetrix is currently unreachable.',
                'data' => [],
                'errors' => [
                    [
                        'field' => 'integration',
                        'message' => $e->getMessage(),
                    ],
                ],
                'http_status' => 500,
            ];
        }
    }
}
