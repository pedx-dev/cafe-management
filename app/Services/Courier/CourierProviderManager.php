<?php

namespace App\Services\Courier;

use App\Services\Courier\Contracts\CourierProviderInterface;
use InvalidArgumentException;

class CourierProviderManager
{
    /**
     * @var array<string, CourierProviderInterface>
     */
    private array $providers = [];

    /**
     * @param iterable<CourierProviderInterface> $providers
     */
    public function __construct(iterable $providers = [])
    {
        foreach ($providers as $provider) {
            $this->register($provider);
        }
    }

    public function register(CourierProviderInterface $provider): void
    {
        $this->providers[$provider->providerKey()] = $provider;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function sendOrder(string $providerKey, array $payload): array
    {
        $provider = $this->providers[$providerKey] ?? null;

        if (! $provider) {
            throw new InvalidArgumentException("Courier provider [{$providerKey}] is not registered.");
        }

        return $provider->sendOrder($payload);
    }

    /**
     * @return array<int, string>
     */
    public function availableProviders(): array
    {
        return array_keys($this->providers);
    }
}
