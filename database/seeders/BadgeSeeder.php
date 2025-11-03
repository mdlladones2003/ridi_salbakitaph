<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Badge, User};
use Illuminate\Support\Carbon;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badgeTypes = ['reporter', 'verifier', 'helper', 'hero'];

        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        $badges = [];

        foreach ($users as $user) {
            $numBadges = rand(0, count($badgeTypes));

            $userBadgeTypes = collect($badgeTypes)->random($numBadges);

            foreach ($userBadgeTypes as $type) {
                $badges[] = [
                    'user_id'    => $user->user_id,
                    'badge_type' => $type,
                    'earned_at'  => Carbon::now()->subDays(rand(0, 365)),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
    }
}
