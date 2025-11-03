<?php

namespace App\Http\Controllers;
use App\Models\{Report, Alert, CheckIn, Post};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $activeAlerts = Alert::where('is_active', true)
            ->where(function($query) {
                $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
            })
            ->orderBy('severity', 'desc')
            ->limit(5)
            ->get();

        $recentReports = Report::with(['user', 'barangay'])
            ->latest('reported_at')
            ->limit(10)
            ->get();

        $recentPosts = Post::with(['author', 'comments', 'reactions'])
            ->latest()
            ->limit(5)
            ->get();

        $stats = [
            'total_reports'     => Report::count(),
            'active_reports'    => Report::whereIn('status', ['pending', 'verified'])->count(),
            'safe_check_ins'    => CheckIn::where('status', 'safe')->whereDate('created_at', today())->count(),
            'need_help'         => CheckIn::where('status', 'need_help')->whereDate('created_at', today())->count()
        ];

        return view('dashboard', compact('activeAlerts', 'recentReports', 'recentPosts', 'stats'));
    }
}
