<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Report, User, Alert, CheckIn};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_reports'     => Report::count(),
            'pending_reports'   => Report::where('status', 'pending')->count(),
            'active_alerts'     => Alert::where('is_active', true)->count(),
            'today_check_ins'   => CheckIn::whereDate('created_at', today())->count()
        ];

        $recentReports = Report::with(['user', 'barangay'])
            ->latest('reported_at')
            ->limit(10)
            ->get();

        $reportByType = Report::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentReports', 'reportByType'));
    }
}
