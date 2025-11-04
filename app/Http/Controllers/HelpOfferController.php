<?php

namespace App\Http\Controllers;
use App\Models\HelpOffer;
use Illuminate\Http\Request;

class HelpOfferController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'offer_type'     => 'required|in:rescue,shelter,medical,supplies',
            'description'    => 'required|string|min:10|max:500',
            'capacity'       => 'nullable|integer|min:1',
            'valid_until'    => 'nullable|date|after:now'
        ]);

        auth()->user()->helpOffers()->create($validated);

        return redirect()->route('community.index')->with('success', 'Help offer created successfully!');
    }

    public function toggleAvailability(HelpOffer $helpOffer)
    {
        if ($helpOffer->user_id !== auth()->id()) {
            abort(403);
        }

        $helpOffer->update(['is_available' => !$helpOffer->is_available]);

        return back()->with('success', 'Availability updated!');
    }
}
