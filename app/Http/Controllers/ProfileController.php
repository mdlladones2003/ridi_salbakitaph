<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->load([
            'badges',
            'posts',
            'reports',
            'checkIns',
            'helpOffers',
            'comments',
            'reactions'
        ]);

        return view('profile.edit', compact('user'));
    }
}
