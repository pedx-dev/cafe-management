<?php

namespace App\Services\Courier;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoMetrixSyncService
{
    /**
     * @return array<string, mixed>
     */
    public function syncFromCafeStatus(Order $order, string $status): array
    {
        $action = $status === 'cancelled' ? 'cancel' : 'sync_status';

        $payload = [
            'source_system' => 'cafe-management',
            'source_order_id' => $order->id,
            'order_code' => $order->order_code,
            'action' => $action,
            'status' => $status,
            'reason' => $action === 'cancel' ? 'Cancelled from Cafe Management admin panel.' : null,
        ];

        return $this->sendAction($payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function sendAction(array $payload): array
    {
        $config = config('services.courier_integration');
        $providerConfig = $config['gometrix'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | DEMO / LOCALHOST CONFIG NOTES
        |--------------------------------------------------------------------------
        | 1) GO_METRIX_BASE_URL should point to the GoMetrix app host/port.
        | 2) GO_METRIX_ACTION_PATH is the endpoint Cafe calls for two-way sync.
        | 3) Keep GO_METRIX_API_KEY in .env and rotate as needed for demos.
        */
        $url = rtrim((string) ($providerConfig['base_url'] ?? ''), '/') . '/' . ltrim((string) ($providerConfig['action_path'] ?? '/api/integrations/cafe/orders/action'), '/');
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

            Log::info('GoMetrix sync action response', [
                'url' => $url,
                'source_order_id' => $payload['source_order_id'] ?? null,
                'action' => $payload['action'] ?? null,
                'http_status' => $response->status(),
                'success' => (bool) ($body['success'] ?? false),
            ]);

            return [
                'success' => $response->successful() && (bool) ($body['success'] ?? false),
                'message' => (string) ($body['message'] ?? 'GoMetrix sync response received.'),
                'data' => $body['data'] ?? [],
                'errors' => $body['errors'] ?? [],
                'http_status' => $response->status(),
            ];
        } catch (\Throwable $e) {
            Log::warning('GoMetrix sync action failed', [
                'source_order_id' => $payload['source_order_id'] ?? null,
                'action' => $payload['action'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to reach GoMetrix sync endpoint.',
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
