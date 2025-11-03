<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{EvacuationRoute, Barangay};
use Illuminate\Http\Request;

class EvacuationRouteController extends Controller
{
    public function index()
    {
        $routes = EvacuationRoute::with('barangay')
            ->latest()
            ->paginate(20);

        return view('admin.evacuation-routes.index', compact('routes'));
    }

    public function create()
    {
        $barangays = Barangay::orderBy('name')->get();
        return view('admin.evacuation-routes.create', compact('barangays'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barangay_id'   => 'required|exists:barangays,barangay_id',
            'route_name'    => 'required|string|max:255',
            'start_point'   => 'required|string|max:255',
            'end_point'     => 'required|string|max:255',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'is_active'     => 'boolean'
        ]);

        EvacuationRoute::create($validated);

        return redirect()->route('admin.evacuation-routes.index')
            ->with('success', 'Evacuation route created successfully!');
    }

    public function show(EvacuationRoute $evacuationRoute)
    {
        $evacuationRoute->load('barangay');
        return view('admin.evacuation-routes.show', compact('evacuationRoute'));
    }

    public function edit(EvacuationRoute $evacuationRoute)
    {
        $barangays = Barangay::orderBy('name')->get();
        return view('admin.evacuation-routes.create', compact('evacuationRoute', 'barangays'));
    }

    public function update(Request $request, EvacuationRoute $evacuationRoute)
    {
        $validated = $request->validate([
            'barangay_id'   => 'required|exists:barangays,barangay_id',
            'route_name'    => 'required|string|max:255',
            'start_point'   => 'required|string|max:255',
            'end_point'     => 'required|string|max:255',
            'latitude'      => 'required|numeric|between:-90,90',
            'longitude'     => 'required|numeric|between:-180,180',
            'is_active'     => 'boolean'
        ]);

        $evacuationRoute->update($validated);

        return redirect()->route('admin.evacuation-routes.index')
            ->with('success', 'Evacuation route updated successfully!');
    }

    public function destroy(EvacuationRoute $evacuationRoute)
    {
        $evacuationRoute->delete();
        return back()->with('success', 'Evacuation route deleted successfully!');
    }

    public function toggleActive(EvacuationRoute $evacuationRoute)
    {
        $evacuationRoute->update(['is_active' => !$evacuationRoute->is_active]);

        $status = $evacuationRoute->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Route {$status} successfully!");
    }
}
