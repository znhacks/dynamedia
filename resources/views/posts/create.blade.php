<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Foto | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<header>
    <div class="logo">Dynamedia</div>
    <nav>
        <a href="{{ route('posts.index') }}">Gallery</a>
        <a href="{{ route('profile.show', auth()->user()->id) }}">Profil</a>
        <a href="{{ route('posts.index') }}">Lihat Posts</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>

<div class="container">
    <div class="form-card">
        <h2>Upload Foto</h2>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">Judul Foto</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Berikan judul untuk foto Anda" required autofocus>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" placeholder="Ceritakan tentang foto ini...">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label>Foto</label>
                <label for="image" class="file-label">
                    <span>Klik untuk memilih foto</span>
                    <small>atau tarik dan lepas di sini</small>
                    <small>Format: JPEG, PNG, GIF. Max 5MB</small>
                </label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <div id="preview"></div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Upload Foto</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
