<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} | Dynamedia</title>
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
    <div class="profile-header">
        <div class="profile-info">
            <div class="avatar">
                @if ($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                @else
                    <div class="avatar-placeholder"></div>
                @endif
            </div>
            <div class="profile-details">
                <h1>{{ $user->name }}</h1>
                <p class="profile-email">{{ $user->email }}</p>
                @if ($user->bio)
                    <p class="bio">{{ $user->bio }}</p>
                @endif
                @auth
                    @if (auth()->user()->id === $user->id)
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="posts-section">
        <h2 class="section-title">Posts ({{ $posts->count() }})</h2>
        @if ($posts->count() > 0)
            <div class="posts-grid">
                @foreach ($posts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="post-card-link">
                        <div class="post-card">
                            <div class="post-image">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="">
                            </div>
                            <div class="post-content">
                                <h3>{{ Str::limit($post->title, 30) }}</h3>
                                <small>{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-posts">Tidak ada posts</div>
        @endif
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
