<?php

namespace App\Http\Controllers;
use App\Models\{Badge, User};
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $badges = $user->badges()->latest('earned_at')->get();

        $availableBadges = [
            'reporter' => [
                'name' => 'Reporter',
                'description' => 'Submit 5 verified reports',
                'icon' => '📝',
                'requirement' => 5,
                'progress' => $user->reports()->where('status', 'verified')->count(),
            ],
            'verifier' => [
                'name' => 'Verifier',
                'description' => 'Verify 10 reports',
                'icon' => '✅',
                'requirement' => 10,
                'progress' => $user->verifications()->count(),
            ],
            'helper' => [
                'name' => 'Helper',
                'description' => 'Provide 3 help offers',
                'icon' => '🤝',
                'requirement' => 3,
                'progress' => $user->helpOffers()->count(),
            ],
            'hero' => [
                'name' => 'Hero',
                'description' => 'Complete 20 verified reports',
                'icon' => '🦸',
                'requirement' => 20,
                'progress' => $user->reports()->where('status', 'verified')->count(),
            ],
        ];

        return view('badges.index', compact('badges', 'availableBadges'));
    }

    public function leaderboard()
    {
        $topReporters = User::withCount(['reports' => function($query) {
            $query->where('status', 'verified');
        }])
            ->orderBy('reports_count', 'desc')
            ->limit(10)
            ->get();

        $topVerifiers = User::withCount('verifications')
            ->orderBy('verifications_count', 'desc')
            ->limit(10)
            ->get();

        $topHelpers = User::withCount('helpOffers')
            ->orderBy('help_offers_count', 'desc')
            ->limit(10)
            ->get();

        $topByReputation = User::orderBy('reputation_score', 'desc')
            ->limit(10)
            ->get();

        return view('badges.leaderboard', compact('topReporters', 'topVerifiers', 'topHelpers', 'topByReputation'));
    }
}
