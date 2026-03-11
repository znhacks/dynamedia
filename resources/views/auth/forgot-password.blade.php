<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Dynamedia</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="page-spacing">

<div class="container">
    <div class="form-card">
        <h2>Lupa Password?</h2>
        <p class="form-description">Masukkan email Anda untuk menerima link reset password</p>

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

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Reset Link</button>
        </form>

        <div class="form-links">
            <a href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </div>
</div>

<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
