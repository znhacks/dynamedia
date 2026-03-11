<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<header>
    <div class="logo">Dynamedia</div>
    <nav>
        <a href="{{ route('posts.index') }}">Gallery</a>
        <a href="{{ route('profile.show', auth()->user()->id) }}">Profil</a>
        <a href="{{ route('posts.create') }}">Upload</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>

<div class="container">
    <div class="form-card">
        <h2>Edit Profil</h2>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="success">✓ {{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="bio">Bio / Tentang Saya</label>
                <textarea id="bio" name="bio" placeholder="Ceritakan tentang diri Anda..." maxlength="500">{{ old('bio', $user->bio ?? '') }}</textarea>
                <small>Maksimal 500 karakter</small>
            </div>

            <div class="form-group">
                <label>Foto Profil (Avatar)</label>
                <div class="avatar-section">
                    <div>
                        @if ($user->avatar)
                            <div class="avatar-preview">
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar {{ $user->name }}">
                            </div>
                            <small>Foto saat ini</small>
                        @else
                            <div class="avatar-preview placeholder">
                                <div>Belum ada foto</div>
                            </div>
                        @endif
                    </div>
                    <div class="avatar-upload">
                        <label for="avatar" class="file-label">
                            <span>Pilih Foto Baru</span>
                            <small>atau tarik dan lepas di sini</small>
                            <small>JPG, JPEG, PNG, WebP - Max 2MB</small>
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp">
                        <div id="previewContainer"></div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route('profile.show', $user->id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
