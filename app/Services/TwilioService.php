<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TwilioService
{
    public function isConfigured(): bool
    {
        return filled(config('services.twilio.sid'))
            && filled(config('services.twilio.auth_token'))
            && filled(config('services.twilio.from'));
    }

    public function sendOrderStatus(User $user, Order $order, string $statusLabel): bool
    {
        if (! $this->isConfigured() || empty($user->phone)) {
            return false;
        }

        $body = sprintf(
            'Cafe update: Your order %s is now %s. Total: PHP %s.',
            $order->order_code ?? ('#' . $order->id),
            Str::lower($statusLabel),
            number_format((float) $order->total_amount, 2)
        );

        return $this->sendMessage((string) $user->phone, $body);
    }

    public function sendMessage(string $to, string $body): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        $sid = (string) config('services.twilio.sid');

        $response = Http::asForm()
            ->withBasicAuth($sid, (string) config('services.twilio.auth_token'))
            ->post('https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/Messages.json', [
                'To' => $to,
                'From' => (string) config('services.twilio.from'),
                'Body' => $body,
            ]);

        return $response->successful();
    }
}
