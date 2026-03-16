<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class GoogleMapsService
{
    public function isConfigured(): bool
    {
        return filled(config('services.google_maps.api_key'));
    }

    /**
     * @return array{lat:float,lng:float,formatted_address:?string}|null
     */
    public function geocode(string $address): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $address,
            'key' => config('services.google_maps.api_key'),
        ]);

        if (! $response->successful()) {
            return null;
        }

        $payload = $response->json();
        if (Arr::get($payload, 'status') !== 'OK') {
            return null;
        }

        $result = Arr::get($payload, 'results.0');
        $lat = Arr::get($result, 'geometry.location.lat');
        $lng = Arr::get($result, 'geometry.location.lng');

        if ($lat === null || $lng === null) {
            return null;
        }

        return [
            'lat' => (float) $lat,
            'lng' => (float) $lng,
            'formatted_address' => Arr::get($result, 'formatted_address'),
        ];
    }
}
