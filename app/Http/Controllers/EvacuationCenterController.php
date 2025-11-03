<?php

namespace App\Http\Controllers;
use App\Models\EvacuationCenter;
use Illuminate\Http\Request;

class EvacuationCenterController extends Controller
{
    public function index()
    {
        $centers = EvacuationCenter::with('barangay')
            ->where('is_active', true)
            ->get();

        return view('evacuation-centers.index', compact('centers'));
    }

    public function show(EvacuationCenter $evacuationCenter)
    {
        $evacuationCenter->load('barangay');

        $occupancyPercentage = $evacuationCenter->capacity > 0
            ? ($evacuationCenter->current_occupancy / $evacuationCenter->capacity) * 100
            : 0;

        return view('evacuation-centers.show', compact('evacuationCenter', 'occupancyPercentage'));
    }
}
