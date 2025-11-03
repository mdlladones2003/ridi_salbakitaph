<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:1|max:500'
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Comment added!');
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:1|max:500'
        ]);

        $comment->update(['content' => $validated['content']]);

        return back()->with('success', 'Comment updated!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted!');
    }
}
