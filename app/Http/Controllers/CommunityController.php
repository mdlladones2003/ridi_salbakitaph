<?php

namespace App\Http\Controllers;

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
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', '!=', 'false_alarm')
            ->latest()
            ->get();

        return view('community.map.index', compact('reports'));
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

    /**
     * Search community content
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('community.index');
        }

        // Search posts
        $posts = Post::with(['author', 'comments.user'])
            ->where('content', 'LIKE', "%{$query}%")
            ->orWhereHas('author', function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%");
            })
            ->withCount(['likes', 'comments'])
            ->latest()
            ->limit(10)
            ->get();

        // Search users
        $users = User::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();

        // Search alerts
        $alerts = Alert::where('title', 'LIKE', "%{$query}%")
            ->orWhere('message', 'LIKE', "%{$query}%")
            ->where('is_active', true)
            ->latest()
            ->limit(5)
            ->get();

        $activeAlertsCount = Alert::where('is_active', true)->count();
        $unreadNotifications = auth()->user()->unreadNotifications()->count();
        $notifications = auth()->user()->notifications()->limit(5)->get();

        return view('community.search', compact(
            'query',
            'posts',
            'users',
            'alerts',
            'activeAlertsCount',
            'unreadNotifications',
            'notifications'
        ));
    }
}
