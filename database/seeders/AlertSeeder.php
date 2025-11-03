<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alert;
use App\Models\DisasterUpdate;

class AlertSeeder extends Seeder
{
    public function run(): void
    {
        $severities = ['info', 'warning', 'critical'];

        $disasters = DisasterUpdate::all();

        if ($disasters->isEmpty()) {
            return;
        }

        foreach ($disasters as $disaster) {
            foreach (range(1, rand(1, 3)) as $i) {
                $severity = fake()->randomElement($severities);

                $messages = [
                    "Residents in {$disaster->affected_area} are advised to stay alert due to the ongoing {$disaster->type}.",
                    "Authorities have issued a {$severity} alert for {$disaster->affected_area} amid the current {$disaster->type}.",
                    "Evacuation is recommended for low-lying areas in {$disaster->affected_area}. Stay tuned for updates.",
                    "Emergency response teams are on standby following reports of {$disaster->type} in {$disaster->affected_area}.",
                    "A {$severity} alert has been raised in {$disaster->affected_area} due to worsening conditions."
                ];

                Alert::create([
                    'disaster_id' => $disaster->disaster_id,
                    'message'     => fake()->randomElement($messages),
                    'severity'    => $severity,
                    'sent_at'     => now()->subHours(rand(0, 72)),
                    'expires_at'  => now()->addHours(rand(6, 48)),
                    'is_active'   => fake()->boolean(70),
                    'created_at'  => now()->subDays(rand(0, 10)),
                    'updated_at'  => now()
                ]);
            }
        }
    }
}
