<?php

namespace App\Http\Controllers;
use App\Models\{Report, Barangay};
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function create()
    {
        $barangays = Barangay::orderBy('name')->get();
        return view('community.reports.create', compact('barangays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'       => 'required|exists:barangays,barangay_id',
            'type'              => 'required|in:flood,fire,earthquake,typhoon,landslide',
            'severity'          => 'required|in:low,moderate,high,critical',
            'content'           => 'required|string|min:10',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'affected_count'    => 'nullable|integer|min:0',
            'media.*'           => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480'
        ]);

        $report = auth()->user()->reports()->create([
            ...$validated,
            'reported_at' => now(),
            'status'      => 'pending',
        ]);

        if ($request->hasFile('media')) {
            $mediaPaths = [];
            foreach ($request->file('media') as $file) {
                $path = $file->store('reports', 'public');
                $mediaPaths[] = $path;
            }

            $report->update(['media' => $mediaPaths]);
        }

        return redirect()
            ->route('community.reports')
            ->with('success', 'Report submitted successfully!');
    }

    public function show(Report $report)
    {
        $report->load(['user', 'barangay', 'verifications.verifier']);

        return view('community.reports.show', compact('report'));
    }
}
