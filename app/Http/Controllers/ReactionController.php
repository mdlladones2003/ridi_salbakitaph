<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'emoji_type' => 'required|in:helpful,warning,folded_hand,sad'
        ]);

        $userId = auth()->id();

        $existing = Reaction::where('user_id', $userId)
            ->where('post_id', $post->post_id)
            ->first();

        if ($existing) {
            if ($existing->emoji_type !== $validated['emoji_type']) {
                $existing->update(['emoji_type' => $validated['emoji_type']]);
            }
        } else {
            Reaction::create([
                'user_id'       => $userId,
                'post_id'       => $post->post_id,
                'emoji_type'    => $validated['emoji_type']
            ]);
        }

        return back()->with('success', 'Reaction updated successfully!');
    }
}
