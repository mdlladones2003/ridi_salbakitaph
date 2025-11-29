<?php

namespace App\Http\Controllers;

use App\Models\{Report, Verification};

class VerificationController extends Controller
{
    public function store(Report $report)
    {
        $userId = auth()->id();

        $existing = Verification::where('report_id', $report->report_id)
            ->where('verifier_id', $userId)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already verified this report.');
        }

        Verification::create([
            'report_id'      => $report->report_id,
            'verifier_id'    => $userId,
            'status'         => 'verified',
            'verified_count' => 1,
            'verified_at'    => now()
        ]);

        $report->increment('report_verified_count');

        $report->refresh();

        if ($report->report_verified_count >= 3 && $report->status === 'pending') {
            $report->update(['status' => 'verified']);
        }

        return back()->with('success', 'Report successfully verified!');
    }
}
