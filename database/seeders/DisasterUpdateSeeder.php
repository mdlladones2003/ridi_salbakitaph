<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DisasterUpdate;

class DisasterUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['flood', 'fire', 'earthquake', 'typhoon', 'landslide'];
        $areas = [
            'Tinambac, Camarines Sur',
            'Naga City, Camarines Sur',
            'Iriga City, Camarines Sur',
            'Bato, Catanduanes',
            'Legazpi City, Albay',
            'Pili, Camarines Sur',
            'Goa, Camarines Sur',
            'Libmanan, Camarines Sur'
        ];

        foreach (range(1, 15) as $i) {
            $type = fake()->randomElement($types);
            $area = fake()->randomElement($areas);

            $contentSamples = [
                "A {$type} has been reported affecting the area of {$area}. Residents are advised to stay alert and follow safety protocols.",
                "Due to heavy rainfall, a {$type} has impacted {$area}. Local authorities are assessing damages.",
                "An ongoing {$type} incident is being monitored in {$area}. Evacuation centers are on standby.",
                "Reports indicate a {$type} causing disruptions in {$area}. Emergency teams have been deployed.",
                "Authorities warn of potential {$type} risks in {$area}. Residents should prepare accordingly."
            ];

            DisasterUpdate::create([
                'type'          => $type,
                'content'       => fake()->randomElement($contentSamples),
                'affected_area' => $area,
                'created_at'    => now()->subDays(rand(0, 30)),
                'updated_at'    => now()
            ]);
        }
    }
}
