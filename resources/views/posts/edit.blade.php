<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<header>
    <div class="logo">Dynamedia</div>
    <nav>
        <a href="{{ route('posts.index') }}">Gallery</a>
        <a href="{{ route('profile.show', auth()->user()->id) }}">Profil</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
</header>

<div class="container">
    <div class="form-card">
        <h2>Edit Foto</h2>

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Judul Foto</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description">{{ old('description', $post->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>Foto Saat Ini</label>
                <div class="current-image">
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                </div>
                <small>Biarkan kosong untuk menggunakan foto yang sama</small>
            </div>

            <div class="form-group">
                <label>Ganti Foto (Opsional)</label>
                <label for="image" class="file-label">
                    <span>Klik untuk memilih foto baru</span>
                    <small>atau tarik dan lepas di sini</small>
                    <small>Format: JPEG, PNG, GIF. Max 5MB</small>
                </label>
                <input type="file" id="image" name="image" accept="image/*">
                <div id="preview"></div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">Batal</a>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('deleteForm').submit();" style="margin-left: auto;">Hapus Post</button>
            </div>
        </form>

        <form method="POST" action="{{ route('posts.destroy', $post) }}" id="deleteForm" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
document.querySelector('.btn-danger').addEventListener('click', function() {
    if (confirm('Yakin ingin menghapus post ini?')) {
        document.getElementById('deleteForm').submit();
    }
});
</script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
