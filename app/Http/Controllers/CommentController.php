<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new comment on a post.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Komentar berhasil ditambahkan');
    }

    /**
     * Delete a comment (only owner can delete).
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('posts.show', $post)
            ->with('success', 'Komentar berhasil dihapus');
    }
}
