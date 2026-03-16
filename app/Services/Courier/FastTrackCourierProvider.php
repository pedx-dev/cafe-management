<?php

namespace App\Services\Courier;

use App\Services\Courier\Contracts\CourierProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastTrackCourierProvider implements CourierProviderInterface
{
    public function providerKey(): string
    {
        return 'fasttrack';
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function sendOrder(array $payload): array
    {
        $config = config('services.courier_integration');
        $providerConfig = $config['fasttrack'] ?? [];

        // Keep network address/ports configurable for localhost-to-LAN demo switching.
        $url = rtrim((string) ($providerConfig['base_url'] ?? ''), '/') . '/' . ltrim((string) ($providerConfig['outbound_order_path'] ?? '/api/orders'), '/');
        $headerName = (string) ($config['api_key_header'] ?? 'X-Integration-Key');
        $apiKey = (string) ($providerConfig['api_key'] ?? '');

        try {
            $request = Http::acceptJson()
                ->asJson()
                ->withHeaders([$headerName => $apiKey])
                ->connectTimeout((int) ($config['connect_timeout_seconds'] ?? 5))
                ->timeout((int) ($config['request_timeout_seconds'] ?? 10))
                ->retry(
                    (int) ($config['max_retries'] ?? 1),
                    (int) ($config['retry_sleep_milliseconds'] ?? 200)
                );

            $response = $request->post($url, $payload);
            $body = $response->json() ?: [];

            Log::info('FastTrack order dispatch response', [
                'url' => $url,
                'http_status' => $response->status(),
                'order_id' => $payload['source_order_id'] ?? null,
                'success' => (bool) ($body['success'] ?? false),
            ]);

            if ($response->successful() && (bool) ($body['success'] ?? false)) {
                return [
                    'success' => true,
                    'message' => (string) ($body['message'] ?? 'Order sent to FastTrack.'),
                    'data' => $body['data'] ?? [],
                    'errors' => $body['errors'] ?? [],
                    'http_status' => $response->status(),
                ];
            }

            return [
                'success' => false,
                'message' => (string) ($body['message'] ?? 'FastTrack rejected the request.'),
                'data' => $body['data'] ?? [],
                'errors' => $body['errors'] ?? [],
                'http_status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            Log::error('FastTrack order dispatch failed', [
                'url' => $url,
                'order_id' => $payload['source_order_id'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'FastTrack is currently unreachable.',
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
