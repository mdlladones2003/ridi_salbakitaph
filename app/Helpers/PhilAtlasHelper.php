<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class PhilAtlasHelper
{
    /**
     * Fetch barangay coordinates (latitude, longitude) from PhilAtlas
     *
     * @param string $municipality
     * @param string $barangay
     * @param string $province
     * @return array [latitude, longitude]
     */
    public static function getCoordinates(string $municipality, string $barangay, string $province = 'Camarines Sur')
    {
        $slugify = fn($text) => strtolower(str_replace([' ', 'ñ', 'Ñ'], ['-', 'n', 'n'], trim($text)));

        $url = sprintf(
            'https://www.philatlas.com/luzon/r05/%s/%s/%s.html',
            $slugify($province),
            $slugify($municipality),
            $slugify($barangay)
        );

        try {
            $response = Http::get($url);
            if ($response->failed()) {
                info("Failed to fetch from $url");
                return [null, null];
            }

            $body = $response->body();

            // More flexible regex to find Latitude and Longitude values in the HTML, including decimal and negative signs
            preg_match('/Latitude<\/strong>\s*:\s*([\d\.\-]+)/i', $body, $latMatch);
            preg_match('/Longitude<\/strong>\s*:\s*([\d\.\-]+)/i', $body, $lngMatch);

            $lat = $latMatch[1] ?? null;
            $lng = $lngMatch[1] ?? null;

            // Double check and sanitize extracted values
            $lat = is_numeric($lat) ? floatval($lat) : null;
            $lng = is_numeric($lng) ? floatval($lng) : null;

            if (!$lat || !$lng) {
                info("No coordinates found for $barangay, $municipality at $url");
            }

            return [$lat, $lng];
        } catch (\Throwable $e) {
            info("Error fetching PhilAtlas data: " . $e->getMessage());
            return [null, null];
        }
    }

}
