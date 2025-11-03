<?php

namespace App\Http\Controllers;
use App\Models\Alert;

class AlertController extends Controller
{
    public function show(Alert $alert)
    {
        $alert->load('disasterUpdate');
        return view('community.alerts.show', compact('alert'));
    }

    public function archive()
    {
        $alerts = Alert::with('disasterUpdate')
            ->where('is_active', false)
            ->orWhere('expires_at', '<=', now())
            ->orderBy('sent_at', 'desc')
            ->paginate(20);

        return view('community.alerts.archive', compact('alerts'));
    }
}
