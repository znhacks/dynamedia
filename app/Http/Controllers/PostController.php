<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of all posts (gallery).
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(12);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show a single post with comments.
     */
    public function show(Post $post)
    {
        $post->load('user', 'comments.user');
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form to create a new post.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a new post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5242880',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        $post = Auth::user()->posts()->create($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post berhasil dibuat');
    }

    /**
     * Show the form to edit a post.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update a post.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5242880',
        ]);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post berhasil diperbarui');
    }

    /**
     * Delete a post (only owner can delete).
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete image file
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post berhasil dihapus');
    }
}
