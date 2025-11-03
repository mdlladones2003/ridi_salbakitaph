<?php

namespace App\Http\Controllers;
use App\Models\{EvacuationRoute, Barangay};
use Illuminate\Http\Request;

class EvacuationRouteController extends Controller
{
    public function index()
    {
        $routes = EvacuationRoute::with('barangay')
            ->where('is_active', true)
            ->orderBy('route_name')
            ->get();

        return view('evacuation-routes.index', compact('routes'));
    }

    public function show(EvacuationRoute $evacuationRoute)
    {
        $evacuationRoute->load('barangay');
        return view('evacuation-routes.show', compact('evacuationRoute'));
    }

    public function byBarangay(Barangay $barangay)
    {
        $routes = $barangay->evacuationRoutes()
            ->where('is_active', true)
            ->get();

        return view('evacuation-routes.by-barangay', compact('routes', 'barangay'));
    }

    public function map()
    {
        $routes = EvacuationRoute::with('barangay')
            ->where('is_active', true)
            ->get();

        return view('evacuation-routes.map', compact('routes'));
    }
}
