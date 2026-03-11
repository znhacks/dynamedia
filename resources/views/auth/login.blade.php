<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<header>
    <div class="logo">Dynamedia</div>
    <nav>
        <a href="/">Gallery</a>
        <a href="{{ route('register') }}">Register</a>
    </nav>
</header>

<div class="form-card">
    <h2>Login</h2>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="remember" id="remember"> Ingat saya
            </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 20px;">Login</button>
    </form>

    <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 10px;">
        <a href="{{ route('password.request') }}" style="color: var(--color-accent-silver); text-decoration: none; font-size: 13px;">Lupa password?</a>
        <small style="color: var(--color-text-secondary);">Belum punya akun?</small>
        <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sekarang</a>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
