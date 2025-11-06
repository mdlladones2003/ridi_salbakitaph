<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\DisasterUpdate;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index(Request $request)
    {
        $query = Alert::withCount('disasterUpdate');

        // Severity filter
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $alerts = $query->latest()->paginate(10);

        return view('admin.alerts.index', compact('alerts'));
    }

    public function create()
    {
        $disasters = DisasterUpdate::latest()->get();

        return view('admin.alerts.create', compact('disasters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'disaster_id'   => 'required|exists:disaster_updates,disaster_id',
            'message'       => 'required|string',
            'severity'      => 'required|in:info,warning,critical',
            'expires_at'    => 'nullable|date|after:now',
        ]);

        Alert::create([
            ...$validated,
            'sent_at'   => now(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.alerts.index')->with('success', 'Alert broadcast successfully!');
    }

    public function toggleActive(Alert $alert)
    {
        $alert->is_active = !$alert->is_active;
        $alert->save();

        return redirect()->route('admin.alerts.index')->with('success', 'Alert status updated successfully!');
    }

    public function destroy(Alert $alert)
    {
        $alert->delete();

        return redirect()->route('admin.alerts.index')->with('success', 'Alert deleted successfully!');
    }
}
