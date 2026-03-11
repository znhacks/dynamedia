<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<header>
    <div class="logo">Dynamedia</div>
    <nav>
        <a href="{{ route('posts.index') }}">Gallery</a>
        @auth
            <a href="{{ route('profile.show', auth()->user()->id) }}">Profil</a>
            <a href="{{ route('posts.create') }}">Upload</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endauth
        @guest
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endguest
    </nav>
</header>

<div class="container">
    <a href="{{ route('posts.index') }}" class="back-link">← Kembali ke Gallery</a>

    <div class="post-header">
        <h1 class="post-title">{{ $post->title }}</h1>
        <div class="post-meta">
            <div class="author-info">
                <div class="author-avatar">
                    @if ($post->user->avatar)
                        <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="">
                    @else
                        <div class="avatar-placeholder"></div>
                    @endif
                </div>
                <div>
                    <div class="author-name"><a href="{{ route('profile.show', $post->user->id) }}">{{ $post->user->name }}</a></div>
                    <div class="post-date">{{ $post->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="post-image">
        <img src="{{ asset('storage/' . $post->image) }}" alt="">
    </div>

    @auth
        @if (auth()->user()->id === $post->user_id)
            <div class="post-actions">
                <a href="{{ route('posts.edit', $post) }}" class="btn btn-success">Edit Post</a>
                <form method="POST" action="{{ route('posts.destroy', $post) }}" class="delete-form" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-post-btn">Hapus Post</button>
                </form>
            </div>
        @endif
    @endauth

    @if ($post->description)
        <div class="post-description">
            <p>{{ $post->description }}</p>
        </div>
    @endif

    <div class="comments-section">
        <h3>Komentar ({{ $post->comments->count() }})</h3>

        @auth
            <div class="comment-form">
                <form method="POST" action="{{ route('comments.store', $post) }}">
                    @csrf

                    @error('content')
                        <div class="errors">{{ $message }}</div>
                    @enderror

                    <textarea name="content" placeholder="Tulis komentar..." required></textarea>
                    <button type="submit">Kirim Komentar</button>
                </form>
            </div>
        @else
            <p class="login-prompt">
                <a href="{{ route('login') }}">Login</a> untuk menambahkan komentar
            </p>
        @endauth

        @if ($post->comments->count() > 0)
            <div class="comments-list">
                @foreach ($post->comments as $comment)
                    <div class="comment">
                        <div class="comment-author">
                            <div class="comment-avatar">
                                @if ($comment->user->avatar)
                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="">
                                @else
                                    <div class="avatar-placeholder"></div>
                                @endif
                            </div>
                            <div class="comment-header">
                                <span class="comment-author-name">{{ $comment->user->name }}</span>
                                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            @auth
                                @if (auth()->user()->id === $comment->user_id)
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-comment-btn">Hapus</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                        <div class="comment-content">{{ $comment->content }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-comments">Belum ada komentar</div>
        @endif
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
<script>
document.querySelectorAll('.delete-post-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Yakin ingin menghapus post ini?')) {
            e.preventDefault();
        }
    });
});

document.querySelectorAll('.delete-comment-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        if (!confirm('Yakin ingin menghapus komentar?')) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>
