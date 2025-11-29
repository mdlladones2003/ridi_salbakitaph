<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'  => 'required|string|min:1',
            'category' => 'required|in:story,tips,update',
            'image'    => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        auth()->user()->posts()->create($validated);

        return back()->with('success', 'Post created successfully!');
    }

    public function update(Request $request, $post_id)
    {
        $post = Post::where('post_id', $post_id)->firstOrFail();

        if ($post->author_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'content'  => 'required|string|min:1',
            'category' => 'required|in:story,tips,update',
            'image'    => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return back()->with('success', 'Post updated successfully!');
    }

    public function destroy($post_id)
    {
        $post = Post::where('post_id', $post_id)->firstOrFail();

        if ($post->author_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return back()->with('success', 'Post deleted successfully!');
    }
}
