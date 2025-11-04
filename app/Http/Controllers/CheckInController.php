<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $latestCheckIn = $user->checkIns()->latest('created_at')->first();

        if (!$request->filled('status')) {
            if ($latestCheckIn) {
                $latestCheckIn->delete();
            }
            return back()->with('success', 'Your status was cleared.');
        }

        $validated = $request->validate([
            'status' => 'required|in:safe,need_help,evacuating'
        ]);

        if ($latestCheckIn) {
            $latestCheckIn->update(['status' => $validated['status']]);
        } else {
            $user->checkIns()->create($validated + ['user_id' => $user->id]);
        }

        return back()->with('success', 'Status updated successfully.');
    }
}

