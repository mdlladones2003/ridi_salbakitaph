<?php

namespace App\Http\Controllers;
use App\Models\{Report, Verification};

class VerificationController extends Controller
{
    public function store(Report $report)
    {
        $existing = Verification::where('report_id', $report->report_id)
            ->where('verifier_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already verified this report.');
        }

        Verification::create([
            'report_id'   => $report->report_id,
            'verifier_id' => auth()->id(),
            'status'      => 'verified',
            'verified_at' => now()
        ]);

        $report->increment('verification_count');

        if ($report->verification_count >= 3 && $report->status === 'pending') {
            $report->update(['status' => 'verified']);
        }

        return back()->with('success', 'Report successfully verified!');
    }
}
