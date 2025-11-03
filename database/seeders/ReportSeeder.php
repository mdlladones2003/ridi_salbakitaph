<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Report, Barangay, User};
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $barangays = Barangay::all();
        $users = User::all();

        if ($barangays->isEmpty()) {
            return;
        }

        if ($users->isEmpty()) {
            return;
        }

        $types = ['flood', 'fire', 'earthquake', 'typhoon', 'landslide'];
        $statuses = ['pending', 'verified', 'resolved', 'false_alarm'];
        $severities = ['low', 'moderate', 'high', 'critical'];

        foreach (range(1, 11) as $i) {
            $barangay = $barangays->random();
            $user = $users->random();
            $type = fake()->randomElement($types);
            $status = fake()->randomElement($statuses);
            $severity = fake()->randomElement($severities);

            $resolvedAt = $status === 'resolved' ? Carbon::now()->subDays(rand(0, 5)) : null;

            Report::create([
                'user_id'           => $user->user_id,
                'barangay_id'       => $barangay->barangay_id,
                'type'              => $type,
                'severity'          => $severity,
                'content'           => match ($type) {
                    'flood'             => "Flooding reported in {$barangay->name}. Residents advised to move to higher ground.",
                    'fire'              => "Fire incident reported near the market area of {$barangay->name}.",
                    'earthquake'        => "Tremors felt in {$barangay->name}, potential aftershocks expected.",
                    'landslide'         => "Landslide reported along hillside areas of {$barangay->name}.",
                    'typhoon'           => "Strong winds and rain affecting {$barangay->name} due to typhoon.",
                    default             => "Incident reported in {$barangay->name}.",
                },
                'status'            => $status,
                'media'             => [fake()->imageUrl(640, 480, 'disaster', true, $type)],
                'latitude'          => $barangay->latitude,
                'longitude'         => $barangay->longitude,
                'verification_count'=> rand(0, 20),
                'affected_count'    => rand(0, 100),
                'reported_at'       => Carbon::now()->subDays(rand(0, 15))->subHours(rand(0, 23)),
                'resolved_at'       => $resolvedAt,
                'created_at'        => now(),
                'updated_at'        => now()
            ]);
        }
    }
}
