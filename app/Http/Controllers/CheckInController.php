<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status'    => 'required|in:safe,need_help,evacuating',
            'notes'     => 'nullable|string|max:500',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $user = auth()->user();

        $latestCheckIn = $user->checkIns()->latest('created_at')->first();

        if ($latestCheckIn) {
            $latestCheckIn->update([
                'status'    => $validated['status'],
                'notes'     => $validated['notes'] ?? null,
                'latitude'  => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null
            ]);
        } else {
            $user->checkIns()->create($validated + ['user_id' => $user->id]);
        }

        return redirect()->route('community.index')->with('success', 'Check-in submitted successfully!');
    }
}

