<?php

namespace App\Http\Controllers;
use App\Models\{Report, Verification};
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function store(Request $request, Report $report)
    {
        // Check if user is volunteer, official, or admin
        if (!in_array(auth()->user()->role, ['volunteer', 'official', 'admin'])) {
            abort(403, 'Only volunteers and officials can verify reports.');
        }

        // Check if user already verified this report
        $existing = Verification::where('report_id', $report->report_id)
            ->where('verifier_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already verified this report.');
        }

        $validated = $request->validate([
            'status' => 'required|in:verified,disputed',
            'notes'  => 'nullable|string|max:500'
        ]);

        Verification::create([
            'report_id'     => $report->report_id,
            'verifier_id'   => auth()->id(),
            'status'        => $validated['status'],
            'notes'         => $validated['notes'] ?? null,
            'verified_at'   => now()
        ]);

        // Increment verification count
        $report->increment('verification_count');

        // If 3 or more verifications, auto-verify the report
        if ($report->verification_count >= 3 && $report->status === 'pending') {
            $report->update(['status' => 'verified']);
        }

        return back()->with('success', 'Verification submitted successfully!');
    }
}
