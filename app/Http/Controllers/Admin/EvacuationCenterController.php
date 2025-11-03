<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{EvacuationCenter, Barangay};
use Illuminate\Http\Request;
use App\Helpers\GeoapifyHelper;

class EvacuationCenterController extends Controller
{
    public function index(Request $request)
    {
        $query = EvacuationCenter::with('barangay');

        // Search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Barangay filter
        if ($request->filled('barangay_id')) {
            $query->where('barangay_id', $request->barangay_id);
        }

        // Status filter
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $centers = $query->latest()->paginate(20);
        $barangays = Barangay::orderBy('name')->get();

        return view('admin.evacuation-centers.index', compact('centers', 'barangays'));
    }

    public function show(EvacuationCenter $evacuationCenter)
    {
        $evacuationCenter->load('barangay');

        $occupancyPercentage = $evacuationCenter->capacity > 0
            ? ($evacuationCenter->current_occupancy / $evacuationCenter->capacity) * 100
            : 0;

        $availableSpace = $evacuationCenter->capacity - $evacuationCenter->current_occupancy;

        return view('admin.evacuation-centers.show', compact('evacuationCenter', 'occupancyPercentage', 'availableSpace'));
    }

    public function create()
    {
        $barangays = Barangay::orderBy('name')->get();
        return view('admin.evacuation-centers.create', compact('barangays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'       => 'required|exists:barangays,barangay_id',
            'name'              => 'required|string|max:255',
            'address'           => 'required|string',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'capacity'          => 'required|integer|min:1',
            'current_occupancy' => 'required|integer|min:0',
            'facilities'        => 'nullable|array',
            'facilities.*'      => 'string|in:medical,food,water,power,blankets,clothing,hygiene',
            'contact_number'    => 'nullable|string|max:20',
            'is_active'         => 'boolean'
        ]);

        if ($validated['current_occupancy'] > $validated['capacity']) {
            return back()->withErrors(['current_occupancy' => 'Occupancy cannot exceed capacity'])->withInput();
        }

        EvacuationCenter::create($validated);

        return redirect()->route('admin.evacuation-centers.index')
            ->with('success', 'Evacuation center created successfully!');
    }

    public function edit(EvacuationCenter $evacuationCenter)
    {
        $barangays = Barangay::orderBy('name')->get();
        return view('admin.evacuation-centers.create', compact('evacuationCenter', 'barangays'));
    }

    public function update(Request $request, EvacuationCenter $evacuationCenter)
    {
        $validated = $request->validate([
            'barangay_id'       => 'required|exists:barangays,barangay_id',
            'name'              => 'required|string|max:255',
            'address'           => 'required|string',
            'latitude'          => 'required|numeric|between:-90,90',
            'longitude'         => 'required|numeric|between:-180,180',
            'capacity'          => 'required|integer|min:1',
            'current_occupancy' => 'required|integer|min:0',
            'facilities'        => 'nullable|array',
            'facilities.*'      => 'string|in:medical,food,water,power,blankets,clothing,hygiene',
            'contact_number'    => 'nullable|string|max:20',
            'is_active'         => 'boolean'
        ]);

        if ($validated['current_occupancy'] > $validated['capacity']) {
            return back()->withErrors(['current_occupancy' => 'Occupancy cannot exceed capacity'])->withInput();
        }

        $evacuationCenter->update($validated);

        return redirect()->route('admin.evacuation-centers.show', $evacuationCenter)
            ->with('success', 'Evacuation center updated successfully!');
    }

    public function destroy(EvacuationCenter $evacuationCenter)
    {
        $evacuationCenter->delete();
        return redirect()->route('admin.evacuation-centers.index')
            ->with('success', 'Evacuation center deleted successfully!');
    }

    public function updateOccupancy(Request $request, EvacuationCenter $evacuationCenter)
    {
        $validated = $request->validate([
            'current_occupancy' => 'required|integer|min:0|max:' . $evacuationCenter->capacity,
        ]);

        $evacuationCenter->update($validated);

        return back()->with('success', 'Occupancy updated successfully!');
    }

    public function toggleStatus(EvacuationCenter $evacuationCenter)
    {
        $evacuationCenter->update(['is_active' => !$evacuationCenter->is_active]);

        $status = $evacuationCenter->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Evacuation center {$status} successfully!");
    }

    public function addFacility(Request $request, EvacuationCenter $evacuationCenter)
    {
        $validated = $request->validate([
            'facility' => 'required|string|in:medical,food,water,power,blankets,clothing,hygiene',
        ]);

        $facilities = $evacuationCenter->facilities ?? [];

        if (!in_array($validated['facility'], $facilities)) {
            $facilities[] = $validated['facility'];
            $evacuationCenter->update(['facilities' => $facilities]);
        }

        return back()->with('success', 'Facility added successfully!');
    }

    public function removeFacility(Request $request, EvacuationCenter $evacuationCenter)
    {
        $validated = $request->validate([
            'facility' => 'required|string'
        ]);

        $facilities = $evacuationCenter->facilities ?? [];
        $facilities = array_values(array_diff($facilities, [$validated['facility']]));

        $evacuationCenter->update(['facilities' => $facilities]);

        return back()->with('success', 'Facility removed successfully!');
    }

    public function barangayInfo(Barangay $barangay)
{
    $latitude = $barangay->latitude;
    $longitude = $barangay->longitude;

    // Build full address from barangay, municipality, and province
    $address = "{$barangay->name}, {$barangay->municipality}, {$barangay->province}, Philippines";

    // Fallback to Geoapify if coordinates missing
    if (!$latitude || !$longitude) {
        [$latitude, $longitude] = GeoapifyHelper::getCoordinates(
            $barangay->municipality,
            $barangay->name,
            $barangay->province
        );
    }

    return response()->json([
        'name' => $barangay->name,
        'municipality' => $barangay->municipality,
        'province' => $barangay->province,
        'address' => $address,
        'latitude' => $latitude,
        'longitude' => $longitude,
    ]);
}

}
