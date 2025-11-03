<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Badge, User};
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index()
    {
        $badges = Badge::with('user')
            ->latest('earned_at')
            ->paginate(50);

        $stats = [
            'total_badges'      => Badge::count(),
            'reporter_badges'   => Badge::where('badge_type', 'reporter')->count(),
            'verifier_badges'   => Badge::where('badge_type', 'verifier')->count(),
            'helper_badges'     => Badge::where('badge_type', 'helper')->count(),
            'hero_badges'       => Badge::where('badge_type', 'hero')->count()
        ];

        return view('admin.badges.index', compact('badges', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'required|exists:users,user_id',
            'badge_type' => 'required|in:reporter,verifier,helper,hero'
        ]);

        // Check if user already has this badge
        $existing = Badge::where('user_id', $validated['user_id'])
            ->where('badge_type', $validated['badge_type'])
            ->first();

        if ($existing) {
            return back()->with('error', 'User already has this badge!');
        }

        Badge::create([
            ...$validated,
            'earned_at' => now(),
        ]);

        // Award reputation points
        $user = User::find($validated['user_id']);
        $reputationBonus = [
            'reporter'  => 50,
            'verifier'  => 75,
            'helper'    => 100,
            'hero'      => 150
        ];

        $user->increment('reputation_score', $reputationBonus[$validated['badge_type']]);

        return back()->with('success', 'Badge awarded successfully!');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();

        return back()->with('success', 'Badge removed successfully!');
    }

    public function autoAward()
    {
        $awarded = 0;

        // Auto-award Reporter badge (5+ verified reports)
        $reporterCandidates = User::withCount(['reports' => function($query) {
            $query->where('status', 'verified');
        }])
            ->having('reports_count', '>=', 5)
            ->get();

        foreach ($reporterCandidates as $user) {
            $hasReporter = Badge::where('user_id', $user->user_id)
                ->where('badge_type', 'reporter')
                ->exists();

            if (!$hasReporter) {
                Badge::create([
                    'user_id'    => $user->user_id,
                    'badge_type' => 'reporter',
                    'earned_at'  => now()
                ]);
                $user->increment('reputation_score', 50);
                $awarded++;
            }
        }

        // Auto-award Verifier badge (10+ verifications)
        $verifierCandidates = User::withCount('verifications')
            ->having('verifications_count', '>=', 10)
            ->get();

        foreach ($verifierCandidates as $user) {
            $hasVerifier = Badge::where('user_id', $user->user_id)
                ->where('badge_type', 'verifier')
                ->exists();

            if (!$hasVerifier) {
                Badge::create([
                    'user_id'    => $user->user_id,
                    'badge_type' => 'verifier',
                    'earned_at'  => now()
                ]);
                $user->increment('reputation_score', 75);
                $awarded++;
            }
        }

        // Auto-award Helper badge (3+ help offers)
        $helperCandidates = User::withCount('helpOffers')
            ->having('help_offers_count', '>=', 3)
            ->get();

        foreach ($helperCandidates as $user) {
            $hasHelper = Badge::where('user_id', $user->user_id)
                ->where('badge_type', 'helper')
                ->exists();

            if (!$hasHelper) {
                Badge::create([
                    'user_id'    => $user->user_id,
                    'badge_type' => 'helper',
                    'earned_at'  => now()
                ]);
                $user->increment('reputation_score', 100);
                $awarded++;
            }
        }

        // Auto-award Hero badge (20+ verified reports)
        $heroCandidates = User::withCount(['reports' => function($query) {
            $query->where('status', 'verified');
        }])
            ->having('reports_count', '>=', 20)
            ->get();

        foreach ($heroCandidates as $user) {
            $hasHero = Badge::where('user_id', $user->user_id)
                ->where('badge_type', 'hero')
                ->exists();

            if (!$hasHero) {
                Badge::create([
                    'user_id'    => $user->user_id,
                    'badge_type' => 'hero',
                    'earned_at'  => now()
                ]);
                $user->increment('reputation_score', 150);
                $awarded++;
            }
        }

        return back()->with('success', "Auto-awarded {$awarded} badges!");
    }
}
