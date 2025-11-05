<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisasterUpdate;
use Illuminate\Http\Request;

class DisasterUpdateController extends Controller
{
    public function index(Request $request)
    {
        $query = DisasterUpdate::withCount('alerts');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('affected_area', 'like', "%{$search}%");
            });
        }

        $disasters = $query->latest()->paginate(20);

        return view('admin.disasters.index', compact('disasters'));
    }

    public function show(DisasterUpdate $disaster)
    {
        $disaster->load(['alerts' => function($query) {
            $query->latest('sent_at');
        }]);

        $stats = [
            'total_alerts'      => $disaster->alerts()->count(),
            'active_alerts'     => $disaster->alerts()->where('is_active', true)->count(),
            'critical_alerts'   => $disaster->alerts()->where('severity', 'critical')->count()
        ];

        return view('admin.disasters.show', compact('disaster', 'stats'));
    }

    public function create()
    {
        return view('admin.disasters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'          => 'required|in:flood,fire,earthquake,typhoon,landslide',
            'content'       => 'required|string|min:10',
            'affected_area' => 'required|string|max:255'
        ]);

        $disaster = DisasterUpdate::create($validated);

        return redirect()->route('admin.disasters.show', $disaster)
            ->with('success', 'Disaster update created successfully!');
    }

    public function edit(DisasterUpdate $disaster)
    {
        return view('admin.disasters.create', compact('disaster'));
    }

    public function update(Request $request, DisasterUpdate $disaster)
    {
        $validated = $request->validate([
            'type'          => 'required|in:flood,fire,earthquake,typhoon,landslide',
            'content'       => 'required|string|min:10',
            'affected_area' => 'required|string|max:255'
        ]);

        $disaster->update($validated);

        return redirect()->route('admin.disasters.index', $disaster)
            ->with('success', 'Disaster update updated successfully!');
    }

    public function destroy(DisasterUpdate $disaster)
    {
        if ($disaster->alerts()->where('is_active', true)->count() > 0) {
            return back()->with('error', 'Cannot delete disaster with active alerts!');
        }

        $disaster->delete();
        return redirect()->route('admin.disasters.index')
            ->with('success', 'Disaster update deleted successfully!');
    }
}
