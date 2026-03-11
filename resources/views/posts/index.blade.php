<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<header>
    <div class="logo">Dynamedia</div>
    <nav>
        <a href="/">Home</a>
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
    <div class="gallery-header">
        <h1>Museum Gallery</h1>
        <p>Koleksi foto dari pengguna Dynamedia</p>
    </div>

    @if ($posts->count() > 0)
        <div class="posts-grid">
            @foreach ($posts as $post)
                <div class="post-card">
                    <a href="{{ route('posts.show', $post) }}">
                        <div class="post-image">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="">
                        </div>
                        <div class="post-info">
                            <h2>{{ Str::limit($post->title, 40) }}</h2>
                            <p>{{ Str::limit($post->description, 80) }}</p>
                            <div class="post-author">
                                <div class="post-author-avatar">
                                    @if ($post->user->avatar)
                                        <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="">
                                    @else
                                        <div class="avatar-placeholder"></div>
                                    @endif
                                </div>
                                <div>{{ $post->user->name }}</div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{ $posts->links() }}
    @else
        <div class="no-posts">
            <p>Belum ada posts</p>
            @auth
                <a href="{{ route('posts.create') }}" class="btn btn-primary">Upload Foto Pertama</a>
            @endauth
        </div>
    @endif
</div>

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
