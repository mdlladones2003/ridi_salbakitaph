<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EvacuationCenter;
use App\Models\Barangay;

class EvacuationCenterSeeder extends Seeder
{
    public function run(): void
    {
        if (Barangay::count() === 0) {
            return;
        }

        $facilities = ['medical', 'food', 'water', 'power', 'blankets', 'clothing', 'hygiene'];

        $barangays = Barangay::all();

        foreach ($barangays as $barangay) {
            $count = rand(1, 2);

            for ($i = 1; $i <= $count; $i++) {
                $capacity = rand(50, 300);
                $current = rand(0, $capacity);

                EvacuationCenter::create([
                    'barangay_id'       => $barangay->barangay_id,
                    'name'              => "{$barangay->name} Evacuation Center {$i}",
                    'address'           => "{$barangay->name}, {$barangay->municipality}, {$barangay->province}, Philippines",
                    'latitude'          => $barangay->latitude + (rand(-10, 10) / 1000),
                    'longitude'         => $barangay->longitude + (rand(-10, 10) / 1000),
                    'capacity'          => $capacity,
                    'current_occupancy' => $current,
                    'facilities'        => array_rand(array_flip($facilities), rand(3, 6)),
                    'contact_number'    => '+63' . rand(9000000000, 9999999999),
                    'is_active'         => rand(0, 1)
                ]);
            }
        }
    }
}
