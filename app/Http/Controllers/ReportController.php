<?php

namespace App\Http\Controllers;

use App\Models\{Report, Barangay};
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'       => 'nullable|exists:barangays,barangay_id',
            'barangay_name'     => 'nullable|string|max:255',
            'municipality'      => 'nullable|string|max:255',
            'risk_level'        => 'nullable|in:low,medium,high',
            'type'              => 'required|in:flood,fire,earthquake,typhoon,landslide',
            'severity'          => 'required|in:low,moderate,high,critical',
            'content'           => 'required|string|min:10',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'affected_count'    => 'nullable|integer|min:0',
            'media.*'           => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480'
        ]);

        $barangayId = $validated['barangay_id'] ?? null;

        if (!$barangayId) {
            $request->validate([
                'barangay_name' => 'required|string|max:255',
                'municipality'  => 'required|string|max:255',
            ]);

            $riskLevel = $validated['risk_level'] ?? 'medium';

            $barangay = Barangay::create([
                'name'        => $request->barangay_name,
                'municipality'=> $request->municipality,
                'province'    => 'Camarines Sur',
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'risk_level'  => $riskLevel
            ]);

            $barangayId = $barangay->barangay_id;
        }

        $report = auth()->user()->reports()->create([
            'barangay_id'   => $barangayId,
            'type'          => $validated['type'],
            'severity'      => $validated['severity'],
            'content'       => $validated['content'],
            'latitude'      => $validated['latitude'],
            'longitude'     => $validated['longitude'],
            'affected_count'=> $validated['affected_count'] ?? 0,
            'reported_at'   => now(),
            'status'        => 'pending',
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
            ->route('community.awareness')
            ->with('success', 'Report submitted successfully!');
    }

    // public function show()
}
