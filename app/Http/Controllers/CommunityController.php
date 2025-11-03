<?php

namespace App\Http\Controllers;

use App\Helpers\GeoapifyHelper;
use App\Models\Post;
use App\Models\CheckIn;
use App\Models\Alert;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $posts = Post::with(['author', 'comments.user', 'reactions'])
            ->latest()
            ->paginate(15);

        $userReactionByPost = [];

        foreach ($posts as $post) {
            $reaction = $post->reactions->firstWhere('user_id', $userId);
            $userReactionByPost[$post->post_id] = $reaction?->emoji_type;
        }

        $checkIns = CheckIn::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $activeUsers = User::where('last_active_at', '>=', now()->subMinutes(15))->get();

        return view('community.index', compact('posts', 'checkIns', 'activeUsers', 'userReactionByPost'));
    }

    public function map()
    {
        $reports = Report::with(['user', 'barangay'])
            ->where('status', '!=', 'false_alarm')
            ->orWhereNull('status')
            ->latest()
            ->get();

        foreach ($reports as $report) {
            if (empty($report->latitude) || empty($report->longitude)) {
                $barangay = $report->barangay;

                if ($barangay) {
                    [$lat, $lng] = GeoapifyHelper::getCoordinates(
                        $barangay->municipality,
                        $barangay->name,
                        $barangay->province
                    );

                    if ($lat && $lng) {
                        $report->update([
                            'latitude'  => $lat,
                            'longitude' => $lng
                        ]);
                    }
                }
            }
        }

        $reports = Report::with(['user', 'barangay'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where(function ($q) {
                $q->where('status', '!=', 'false_alarm')
                ->orWhereNull('status');
            })
            ->latest()
            ->get();

        return view('community.map.index', compact('reports'));
    }

    public function reports()
    {
        $user = auth()->user();

        $reports = Report::with(['user', 'barangay'])
            ->latest()
            ->get();

        return view('community.reports.index', compact('reports'));
    }


    public function alerts()
    {
        $alerts = Alert::with('disasterUpdate')
            ->where('is_active', true)
            ->where(function($query) {
                $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
            })
            ->orderByRaw("FIELD(severity, 'critical', 'warning', 'info')")
            ->orderBy('sent_at', 'desc')
            ->paginate(20);

        return view('community.alerts.index', compact('alerts'));
    }

    public function search(Request $request)
    {
        $query = trim($request->input('q'));

        if (!$query) {
            return view('community.search', [
                'query' => '',
                'posts' => collect(),
                'users' => collect(),
                'alerts' => collect(),
                'activeAlertsCount' => Alert::where('is_active', true)->count(),
            ]);
        }

        $posts = Post::with(['author', 'comments.user'])
            ->where(function ($q) use ($query) {
                $q->where('content', 'LIKE', "%{$query}%")
                  ->orWhereHas('author', function ($sub) use ($query) {
                    $sub->where('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('last_name', 'LIKE', "%{$query}%");
                });
            })
            ->withCount(['reactions', 'comments'])
            ->latest()
            ->limit(10)
            ->get();

        $users = User::where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->with([
                'posts' => fn($q) => $q->latest()->limit(3),
                'reports' => fn($q) => $q->latest()->limit(3),
                'helpOffers' => fn($q) => $q->latest()->limit(3),
            ])
            ->limit(10)
            ->get();

        $alerts = Alert::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('message', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->limit(5)
            ->get();

        $activeAlertsCount = Alert::where('is_active', true)->count();

        return view('community.search', compact(
            'query',
            'posts',
            'users',
            'alerts',
            'activeAlertsCount'
        ));
    }
}
