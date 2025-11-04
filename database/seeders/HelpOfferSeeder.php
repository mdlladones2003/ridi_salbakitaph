<?php

namespace Database\Seeders;

use App\Models\HelpOffer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HelpOfferSeeder extends Seeder
{
    public function run(): void
    {
        $helpOffers = [
            [
                'user_id' => 1,
                'offer_type' => 'rescue',
                'description' => 'Offering boat rescue assistance for flood-affected areas.',
                'is_available' => true,
                'capacity' => 5,
                'valid_until' => Carbon::now()->addDays(7)
            ],
            [
                'user_id' => 2,
                'offer_type' => 'shelter',
                'description' => 'Providing temporary shelter for displaced families in Barangay San Isidro.',
                'is_available' => true,
                'capacity' => 20,
                'valid_until' => Carbon::now()->addDays(14)
            ],
            [
                'user_id' => 3,
                'offer_type' => 'medical',
                'description' => 'Volunteer nurse available for first aid and wound care.',
                'is_available' => true,
                'capacity' => 10,
                'valid_until' => Carbon::now()->addDays(3)
            ],
            [
                'user_id' => 4,
                'offer_type' => 'supplies',
                'description' => 'Distributing food packs and bottled water for affected families.',
                'is_available' => false,
                'capacity' => null,
                'valid_until' => Carbon::now()->addDays(5)
            ],
        ];

        foreach ($helpOffers as $offer) {
            HelpOffer::create($offer);
        }
    }
}
