<?php

namespace App\Http\Controllers;

use App\Helpers\GeoapifyHelper;
use App\Models\Post;
use App\Models\CheckIn;
use App\Models\Alert;
use App\Models\Barangay;
use App\Models\DisasterUpdate;
use App\Models\HelpOffer;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $activeUsers = User::where('last_active_at', '>=', now()->subMinutes(15))->get();

        $posts = Post::with(['author', 'comments.user', 'reactions'])
            ->latest()
            ->paginate(15);

        $userReactionByPost = [];
        foreach ($posts as $post) {
            $reaction = $post->reactions->firstWhere('user_id', $userId);
            $userReactionByPost[$post->post_id] = $reaction?->emoji_type;
        }

        $offers = HelpOffer::with('user')
            ->where('is_available', true)
            ->latest()
            ->get();

        // Badge Leaderboards
        $topReporters = User::withCount(['reports as report_verified_count' => function ($query) {
                $query->whereIn('status', ['verified', 'resolved']);
            }])
            ->having('report_verified_count', '>', 0)
            ->orderByDesc('report_verified_count')
            ->limit(5)
            ->get();

        $topVerifiers = User::withCount(['verifications as verified_count' => function ($query) {
                $query->where('status', 'verified');
            }])
            ->having('verified_count', '>', 0)
            ->orderByDesc('verified_count')
            ->limit(5)
            ->get();

        $topHelpers = User::withCount(['helpOffers as help_offers_count' => function ($query) {
                    $query->where('is_available', true);
            }])
            ->having('help_offers_count', '>', 0)
            ->orderByDesc('help_offers_count')
            ->limit(5)
            ->get();

        $topByReputation = User::orderBy('reputation_score', 'desc')
            ->orderByDesc('reputation_score')
            ->limit(5)
            ->get();

        return view('community.index', compact(
            'activeUsers',
            'userReactionByPost',
            'posts',
            'offers',
            'topReporters',
            'topVerifiers',
            'topHelpers',
            'topByReputation'
        ));
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

    public function awareness(Request $request)
    {
        $barangays = Barangay::orderBy('name')->get();
        $activeTab = $request->query('tab', 'reports');

        $currentPage = [
            'reports' => $request->query('reports_page', 1),
            'alerts' => $request->query('alerts_page', 1),
            'disasters' => $request->query('disasters_page', 1),
        ];

        foreach ($currentPage as $key => $page) {
            if ($activeTab !== $key) {
                $currentPage[$key] = 1;
            }
        }

        $reports = Report::with(['user', 'barangay'])
            ->latest()
            ->paginate(6, ['*'], 'reports_page', $currentPage['reports'])
            ->appends(['tab' => 'reports']);

        $reports->each(function ($report) {
            $report->verified_by_me = $report->verifications()
                ->where('verifier_id', auth()->id())
                ->exists();
        });

        $alerts = Alert::with('disasterUpdate')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
            })
            ->orderByRaw("FIELD(severity, 'critical', 'warning', 'info')")
            ->orderBy('sent_at', 'desc')
            ->paginate(6, ['*'], 'alerts_page', $currentPage['alerts'])
            ->appends(['tab' => 'alerts']);

        $disasters = DisasterUpdate::withCount('alerts')
            ->latest()
            ->paginate(6, ['*'], 'disasters_page', $currentPage['disasters'])
            ->appends(['tab' => 'disasters']);

        return view('community.awareness.index', compact(
            'barangays',
            'reports',
            'alerts',
            'disasters',
            'activeTab'
        ));
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
