<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    public function index(Request $request)
    {
        $query = Barangay::withCount(['reports', 'evacuationCenters', 'evacuationRoutes']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('municipality', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%");
            });
        }

        // Risk level filter
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        // Province filter
        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }

        $barangays = $query->orderBy('name')->paginate(20);

        $provinces = Barangay::select('province')->distinct()->orderBy('province')->pluck('province');

        return view('admin.barangays.index', compact('barangays', 'provinces'));
    }

    public function show(Barangay $barangay)
    {
        $barangay->load(['reports' => function($query) {
            $query->latest()->limit(10);
        }, 'evacuationCenters', 'evacuationRoutes']);

        $stats = [
            'total_reports'         => $barangay->reports()->count(),
            'pending_reports'       => $barangay->reports()->where('status', 'pending')->count(),
            'verified_reports'      => $barangay->reports()->where('status', 'verified')->count(),
            'critical_reports'      => $barangay->reports()->where('severity', 'critical')->count(),
            'evacuation_centers'    => $barangay->evacuationCenters()->count(),
            'evacuation_routes'     => $barangay->evacuationRoutes()->count(),
            'active_centers'        => $barangay->evacuationCenters()->where('is_active', true)->count()
        ];

        $reportsByType = $barangay->reports()
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();

        return view('admin.barangays.show', compact('barangay', 'stats', 'reportsByType'));
    }

    public function create()
    {
        return view('admin.barangays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'municipality'  => 'required|string|max:255',
            'province'      => 'required|string|max:255',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'risk_level'    => 'required|in:low,medium,high'
        ]);

        Barangay::create($validated);

        return redirect()->route('admin.barangays.index')->with('success', 'Barangay created successfully!');
    }

    public function edit(Barangay $barangay)
    {
        return view('admin.barangays.create', compact('barangay'));
    }

    public function update(Request $request, Barangay $barangay)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'municipality'  => 'required|string|max:255',
            'province'      => 'required|string|max:255',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'risk_level'    => 'required|in:low,medium,high'
        ]);

        $barangay->update($validated);

        return redirect()->route('admin.barangays.show', $barangay)->with('success', 'Barangay updated successfully!');
    }

    public function destroy(Barangay $barangay)
    {
        // Check if barangay has related records
        if ($barangay->reports()->count() > 0) {
            return back()->with('error', 'Cannot delete barangay with existing reports!');
        }

        $barangay->delete();
        return redirect()->route('admin.barangays.index')->with('success', 'Barangay deleted successfully!');
    }

    public function updateRiskLevel(Request $request, Barangay $barangay)
    {
        $validated = $request->validate([
            'risk_level' => 'required|in:low,medium,high',
        ]);

        $barangay->update($validated);

        return back()->with('success', 'Risk level updated successfully!');
    }
}
