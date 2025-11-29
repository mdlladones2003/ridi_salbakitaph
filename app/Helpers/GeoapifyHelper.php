<?php

namespace App\Helpers;

use App\Models\Barangay;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoapifyHelper
{
    public static function reverseGeocode($lat, $lon)
    {
        try {
            $url = "https://api.geoapify.com/v1/geocode/reverse?lat={$lat}&lon={$lon}&apiKey=" . config('services.geoapify.key') . "&lang=en";
            $response = Http::timeout(10)->get($url);

            if ($response->failed()) {
                throw new \Exception("Geoapify request failed: " . $response->body());
            }

            $data = $response->json();

            $feature = $data['features'][0]['properties'] ?? null;
            if (!$feature) {
                return [
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'formatted_address' => null,
                    'barangay' => null,
                    'municipality' => null,
                    'province' => null,
                ];
            }

            $barangay = $feature['suburb'] ?? $feature['hamlet'] ?? $feature['street'] ?? null;
            $municipality = $feature['city'] ?? $feature['county'] ?? $feature['town'] ?? null;
            $province = $feature['state'] ?? null;

            return [
                'latitude' => $lat,
                'longitude' => $lon,
                'formatted_address' => $feature['formatted'] ?? $feature['address_line2'] ?? null,
                'barangay' => $barangay,
                'municipality' => $municipality,
                'province' => $province,
            ];
        } catch (\Throwable $e) {
            Log::error('Geoapify reverse geocode failed', [
                'error' => $e->getMessage(),
                'lat' => $lat,
                'lon' => $lon,
            ]);

            return [
                'latitude' => $lat,
                'longitude' => $lon,
                'formatted_address' => null,
                'barangay' => null,
                'municipality' => null,
                'province' => null,
            ];
        }
    }
}
