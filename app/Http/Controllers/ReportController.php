<?php

namespace App\Http\Controllers;
use App\Models\{Report, Barangay};
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create()
    {
        $barangays = Barangay::orderBy('name')->get();
        return view('reports.create', compact('barangays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'       => 'required|exists:barangays,barangay_id',
            'type'              => 'required|in:flood,fire,earthquake,typhoon,landslide',
            'severity'          => 'required|in:low,medium,high,critical',
            'content'           => 'required|string|min:10',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'affected_count'    => 'nullable|integer|min:0'
        ]);

        $report = auth()->user()->reports()->create([
            ...$validated,
            'reported_at'   => now(),
            'status'        => 'pending'
        ]);

        return redirect()->route('reports.show', $report)->with('success', 'Report submitted successfully!');
    }

    public function show(Report $report)
    {
        $report->load(['user', 'barangay', 'verifications.verifier']);
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        // Check if user owns this report
        if ($report->user_id !== auth()->id() && !auth()->user()->isOfficial()) {
            abort(403);
        }

        $barangays = Barangay::orderBy('name')->get();

        return view('reports.edit', compact('report', 'barangays'));
    }

    public function update(Request $request, Report $report)
    {
        // Check if user owns this report
        if ($report->user_id !== auth()->id() && !auth()->user()->isOfficial()) {
            abort(403);
        }

        $validated = $request->validate([
            'barangay_id'       => 'required|exists:barangays,barangay_id',
            'type'              => 'required|in:flood,fire,earthquake,typhoon,landslide',
            'severity'          => 'required|in:low,medium,high,critical',
            'content'           => 'required|string|min:10',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'affected_count'    => 'nullable|integer|min:0'
        ]);

        $report->update($validated);

        return redirect()->route('reports.show', $report)->with('success', 'Report updated successfully!');
    }

    public function destroy(Report $report)
    {
        // Check if user owns this report
        if ($report->user_id !== auth()->id() && !auth()->user()->isOfficial()) {
            abort(403);
        }

        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Report deleted successfully!');
    }
}
