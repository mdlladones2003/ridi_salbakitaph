<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use Illuminate\Http\Request;
use App\Helpers\GeoapifyHelper;

class BarangayController extends Controller
{
    public function index(Request $request)
    {
        $query = Barangay::withCount(['reports']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('municipality', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%");
            });
        }

        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }

        $barangays = $query->orderBy('name')->paginate(20);
        $provinces = Barangay::select('province')->distinct()->orderBy('province')->pluck('province');

        return view('admin.barangays.index', compact('barangays', 'provinces'));
    }

    public function show(Barangay $barangay)
    {
        $barangay->load([
            'reports' => fn($query) => $query->latest()->limit(10)
        ]);

        $stats = [
            'total_reports'      => $barangay->reports()->count(),
            'pending_reports'    => $barangay->reports()->where('status', 'pending')->count(),
            'verified_reports'   => $barangay->reports()->where('status', 'verified')->count(),
            'critical_reports'   => $barangay->reports()->where('severity', 'critical')->count()
        ];

        $reportsByType = $barangay->reports()
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->get();

        return view('admin.barangays.show', compact('barangay', 'stats', 'reportsByType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'province'     => 'required|string|max:255',
            'risk_level'   => 'required|in:low,medium,high',
        ]);

        [$lat, $lng] = GeoapifyHelper::getCoordinates(
            $validated['municipality'],
            $validated['name'],
            $validated['province']
        );

        $validated['latitude']  = $lat ?? $request->input('latitude');
        $validated['longitude'] = $lng ?? $request->input('longitude');

        if (!$validated['latitude'] || !$validated['longitude']) {
            return back()->withInput()->with('error', 'Unable to fetch coordinates for this barangay. Please enter them manually.');
        }

        Barangay::create($validated);

        return redirect()->route('admin.barangays.index')->with('success', 'Barangay created successfully!');
    }

    public function update(Request $request, Barangay $barangay)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'province'     => 'required|string|max:255',
            'risk_level'   => 'required|in:low,medium,high',
        ]);

        $locationChanged =
            $validated['name'] !== $barangay->name ||
            $validated['municipality'] !== $barangay->municipality ||
            $validated['province'] !== $barangay->province;

        if ($locationChanged) {
            [$lat, $lng] = GeoapifyHelper::getCoordinates(
                $validated['municipality'],
                $validated['name'],
                $validated['province']
            );

            $validated['latitude']  = $lat ?? $barangay->latitude;
            $validated['longitude'] = $lng ?? $barangay->longitude;
        } else {
            $validated['latitude']  = $barangay->latitude;
            $validated['longitude'] = $barangay->longitude;
        }

        if (!$validated['latitude'] || !$validated['longitude']) {
            return back()->withInput()->with('error', 'Unable to fetch coordinates for this barangay. Please enter them manually.');
        }

        $barangay->update($validated);

        return redirect()->route('admin.barangays.show', $barangay)->with('success', 'Barangay updated successfully!');
    }

    public function destroy(Barangay $barangay)
    {
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

    public function fetchCoordinates(Request $request)
    {
        $validated = $request->validate([
            'barangay'    => 'required|string',
            'municipality'=> 'required|string',
            'province'    => 'nullable|string'
        ]);

        [$lat, $lng] = GeoapifyHelper::getCoordinates(
            $validated['municipality'],
            $validated['barangay'],
            $validated['province'] ?? 'Camarines Sur'
        );

        return response()->json([
            'latitude'  => $lat,
            'longitude' => $lng
        ]);
    }
}
