<?php

namespace App\Http\Controllers;
use App\Models\DisasterUpdate;
use Illuminate\Http\Request;

class DisasterUpdateController extends Controller
{
    public function index()
    {
        $disasters = DisasterUpdate::withCount('alerts')
            ->latest()
            ->paginate(15);

        return view('disasters.index', compact('disasters'));
    }

    public function show(DisasterUpdate $disaster)
    {
        $disaster->load(['alerts' => function($query) {
            $query->latest('sent_at');
        }]);

        return view('disasters.show', compact('disaster'));
    }

    public function byType($type)
    {
        $validTypes = ['flood', 'fire', 'earthquake', 'typhoon', 'landslide'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        $disasters = DisasterUpdate::where('type', $type)
            ->withCount('alerts')
            ->latest()
            ->paginate(15);

        return view('disasters.by-type', compact('disasters', 'type'));
    }
}
