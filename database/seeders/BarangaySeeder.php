<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barangay;
use App\Helpers\GeoapifyHelper;

class BarangaySeeder extends Seeder
{
    public function run(): void
    {
        $barangays = [
            ['name' => 'Binalay', 'municipality' => 'Tinambac', 'province' => 'Camarines Sur', 'risk_level' => 'medium'],
            ['name' => 'Filarca', 'municipality' => 'Tinambac', 'province' => 'Camarines Sur', 'risk_level' => 'high'],
            ['name' => 'Caloco', 'municipality' => 'Tinambac', 'province' => 'Camarines Sur', 'risk_level' => 'low'],
            ['name' => 'San Isidro', 'municipality' => 'Lagonoy', 'province' => 'Camarines Sur', 'risk_level' => 'medium'],
            ['name' => 'Magsaysay', 'municipality' => 'Nabua', 'province' => 'Camarines Sur', 'risk_level' => 'high'],
            ['name' => 'Kilantaao', 'municipality' => 'Sagnay', 'province' => 'Camarines Sur', 'risk_level' => 'medium'],
            ['name' => 'San Juan Bautista', 'municipality' => 'Goa', 'province' => 'Camarines Sur', 'risk_level' => 'low'],
            ['name' => 'Matacla', 'municipality' => 'Goa', 'province' => 'Camarines Sur', 'risk_level' => 'medium'],
            ['name' => 'Santa Cruz', 'municipality' => 'Lagonoy', 'province' => 'Camarines Sur', 'risk_level' => 'high'],
            ['name' => 'Dolorosa', 'municipality' => 'Nabua', 'province' => 'Camarines Sur', 'risk_level' => 'low'],
        ];

        foreach ($barangays as $data) {
            $barangay = $data['name'];
            $municipality = $data['municipality'];
            $province = $data['province'];

            [$lat, $lng] = GeoapifyHelper::getCoordinates($municipality, $barangay, $province);

            if (empty($lat) || empty($lng)) {
                [$lat, $lng] = GeoapifyHelper::getCoordinates($municipality, '', $province);
            }

            if (empty($lat) || empty($lng)) {
                $lat = 13.5250;
                $lng = 123.3000;
            }

            Barangay::updateOrCreate(
                [
                    'name' => $barangay,
                    'municipality' => $municipality,
                ],
                [
                    'province' => $province,
                    'risk_level' => $data['risk_level'],
                    'latitude' => $lat,
                    'longitude' => $lng,
                ]
            );

            usleep(400000);
        }
    }
}
