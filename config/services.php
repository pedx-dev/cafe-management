<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/auth/google/callback'),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
        'js_api_key' => env('GOOGLE_MAPS_JS_API_KEY', env('GOOGLE_MAPS_API_KEY')),
    ],

    'stripe' => [
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency' => env('STRIPE_CURRENCY', 'php'),
        'base_url' => env('STRIPE_BASE_URL', 'https://api.stripe.com/v1'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from' => env('TWILIO_FROM_NUMBER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Courier Integration (Phase 1 Shared Foundation)
    |--------------------------------------------------------------------------
    |
    | Network switch notes for local demos:
    | 1) Change FASTTRACK_BASE_URL when port changes (example: :8001 -> :9001).
    | 2) Replace localhost/127.0.0.1 with your LAN IP when testing on 2 machines.
    |    Example: http://192.168.1.50:8001
    | 3) Keep all endpoint paths here so controllers/services do not hardcode URLs.
    | 4) If browser calls cross ports/domains, update CORS in both apps accordingly.
    | 5) Rotate API keys by changing env only; do not commit real keys.
    |
    */
    'courier_integration' => [
        'default_provider' => env('COURIER_PROVIDER_DEFAULT', 'fasttrack'),
        'api_key_header' => env('COURIER_API_KEY_HEADER', 'X-Integration-Key'),
        'connect_timeout_seconds' => (int) env('COURIER_CONNECT_TIMEOUT_SECONDS', 5),
        'request_timeout_seconds' => (int) env('COURIER_REQUEST_TIMEOUT_SECONDS', 10),
        'max_retries' => (int) env('COURIER_MAX_RETRIES', 1),
        'retry_sleep_milliseconds' => (int) env('COURIER_RETRY_SLEEP_MILLISECONDS', 200),

        'fasttrack' => [
            'base_url' => env('FASTTRACK_BASE_URL', 'http://127.0.0.1:8001'),
            'outbound_order_path' => env('FASTTRACK_OUTBOUND_ORDER_PATH', '/api/orders'),
            'status_callback_path' => env('FASTTRACK_STATUS_CALLBACK_PATH', '/api/fasttrack/status-update'),
            'api_key' => env('FASTTRACK_API_KEY'),
        ],

        /*
        |--------------------------------------------------------------------------
        | GoMetrix integration settings (Finals demo API bridge)
        |--------------------------------------------------------------------------
        | LOCALHOST SETUP:
        | - Keep GO_METRIX_BASE_URL aligned with GoMetrix `php artisan serve`.
        |   Default expected: http://127.0.0.1:8001
        |
        | SAME-NETWORK SETUP (2 machines):
        | - Replace 127.0.0.1 with the machine LAN IP running GoMetrix.
        |   Example: http://192.168.1.20:8001
        |
        | ENDPOINT PATHS:
        | - outbound_order_path = Cafe -> GoMetrix order import endpoint.
        | - status_callback_path = GoMetrix -> Cafe status callback endpoint.
        | - action_path = Cafe -> GoMetrix two-way sync action endpoint.
        |
        | DEMO NOTE:
        | - This integration is intentionally simple (API key + direct HTTP) for
        |   classroom demonstration. Production deployments should add stronger
        |   auth, queues, and idempotency controls.
        */
        'gometrix' => [
            'base_url' => env('GO_METRIX_BASE_URL', 'http://127.0.0.1:8001'),
            'outbound_order_path' => env('GO_METRIX_OUTBOUND_ORDER_PATH', '/api/integrations/cafe/orders'),
            'status_callback_path' => env('GO_METRIX_STATUS_CALLBACK_PATH', '/api/gometrix/status-update'),
            'action_path' => env('GO_METRIX_ACTION_PATH', '/api/integrations/cafe/orders/action'),
            'api_key' => env('GO_METRIX_API_KEY'),
        ],
    ],

    // ...existing code...

];
