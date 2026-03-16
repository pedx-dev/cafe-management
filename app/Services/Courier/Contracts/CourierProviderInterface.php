<?php

namespace App\Services\Courier\Contracts;

interface CourierProviderInterface
{
    public function providerKey(): string;

    /**
     * Send a canonical order payload to the provider.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function sendOrder(array $payload): array;
}
