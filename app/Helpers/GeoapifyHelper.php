<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoapifyHelper
{
    public static function getCoordinates(string $municipality, string $barangay = '', string $province = 'Camarines Sur')
    {
        $apiKey = env('GEOAPIFY_API_KEY');
        if (!$apiKey) {
            return [null, null];
        }

        $query = trim(sprintf('%s, %s, %s, Philippines', $barangay, $municipality, $province), ', ');
        $coordinates = self::fetchCoordinates($query, $apiKey);

        if (self::isGenericResult($coordinates, $municipality, $province, $apiKey)) {
            $queryWithKeyword = trim(sprintf('Barangay %s, %s, %s, Philippines', $barangay, $municipality, $province), ', ');
            $coordinates = self::fetchCoordinates($queryWithKeyword, $apiKey);
        }

        if (empty($coordinates[0]) || empty($coordinates[1])) {
            $fallbackQuery = trim(sprintf('%s, %s, Philippines', $barangay, $province), ', ');
            $coordinates = self::fetchCoordinates($fallbackQuery, $apiKey);
        }

        if ($coordinates[0] && $coordinates[1]) {
            $coordinates[0] = round((float) $coordinates[0], 14);
            $coordinates[1] = round((float) $coordinates[1], 14);
        }

        return $coordinates;
    }

    protected static function fetchCoordinates(string $query, string $apiKey): array
    {
        try {
            $response = Http::get('https://api.geoapify.com/v1/geocode/search', [
                'text' => $query,
                'apiKey' => $apiKey,
                'limit' => 5,
            ]);

            if ($response->failed()) {
                return [null, null];
            }

            $data = $response->json();
            if (empty($data['features'])) {
                return [null, null];
            }

            $bestMatch = collect($data['features'])->first(function ($feature) {
                $category = $feature['properties']['result_type'] ?? '';
                return in_array($category, ['village', 'suburb', 'locality', 'neighbourhood']);
            }) ?? $data['features'][0];

            $coords = $bestMatch['geometry']['coordinates'] ?? [null, null];
            $lon = $coords[0] ?? null;
            $lat = $coords[1] ?? null;

            if (!is_numeric($lat) || !is_numeric($lon)) {
                return [null, null];
            }

            return [$lat, $lon];
        } catch (\Throwable $e) {
            return [null, null];
        }
    }

    protected static function isGenericResult(array $coordinates, string $municipality, string $province, string $apiKey): bool
    {
        if (empty($coordinates[0]) || empty($coordinates[1])) {
            return true;
        }

        $municipalityCoords = self::fetchCoordinates("{$municipality}, {$province}, Philippines", $apiKey);

        if (empty($municipalityCoords[0]) || empty($municipalityCoords[1])) {
            return false;
        }

        $distance = self::calculateDistance($coordinates, $municipalityCoords);
        return $distance < 0.1; // in km (~100 meters)
    }

    protected static function calculateDistance(array $a, array $b): float
    {
        [$lat1, $lon1] = $a;
        [$lat2, $lon2] = $b;
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $h = sin($dLat / 2) ** 2 + sin($dLon / 2) ** 2 * cos($lat1) * cos($lat2);
        return 2 * $earthRadius * asin(min(1, sqrt($h)));
    }
}
