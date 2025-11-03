<?php

namespace App\Http\Controllers;
use App\Models\HelpOffer;
use Illuminate\Http\Request;

class HelpOfferController extends Controller
{
    public function index()
    {
        $offers = HelpOffer::with('user')
            ->where('is_available', true)
            ->latest()
            ->paginate(20);

        return view('help-offers.index', compact('offers'));
    }

    public function create()
    {
        return view('help-offers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'offer_type'     => 'required|in:rescue,shelter,medical,supplies',
            'description'    => 'required|string|min:10|max:500',
            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',
            'capacity'       => 'nullable|integer|min:1',
            'valid_until'    => 'nullable|date|after:now'
        ]);

        auth()->user()->helpOffers()->create($validated);

        return redirect()->route('help-offers.index')->with('success', 'Help offer created successfully!');
    }

    public function destroy(HelpOffer $helpOffer)
    {
        if ($helpOffer->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $helpOffer->delete();
        return back()->with('success', 'Help offer removed!');
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
