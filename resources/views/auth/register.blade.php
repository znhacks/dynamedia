<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<header>
    <div class="logo">Dynamedia</div>
    <nav>
        <a href="/">Gallery</a>
        <a href="{{ route('login') }}">Login</a>
    </nav>
</header>

<div class="form-card">
    <h2>Register</h2>

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

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Daftar</button>
    </form>

    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--color-border);">
        <small style="color: var(--color-text-secondary); display: block; margin-bottom: 10px;">Sudah punya akun?</small>
        <a href="{{ route('login') }}" class="btn btn-primary">Login Sekarang</a>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
