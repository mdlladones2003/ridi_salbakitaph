<?php

namespace App\Http\Controllers;
use App\Models\Barangay;
use Illuminate\Http\Request;

class BarangayController extends Controller
{
    public function index()
    {
        $barangays = Barangay::withCount('reports')
            ->orderBy('name')
            ->paginate(20);

        return view('barangays.index', compact('barangays'));
    }

    public function show(Barangay $barangay)
    {
        $barangay->load(['reports', 'evacuationCenters']);

        $stats = [
            'total_reports'      => $barangay->reports()->count(),
            'active_reports'     => $barangay->reports()->whereIn('status', ['pending', 'verified'])->count(),
            'evacuation_centers' => $barangay->evacuationCenters()->count(),
        ];

        return view('barangays.show', compact('barangay', 'stats'));
    }
}
