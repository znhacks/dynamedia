<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dynamedia</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- Page Transition Overlay -->
<div class="page-transition-overlay"></div>

<header class="topbar">
    <div class="logo">Dynamedia</div>

    <nav class="auth-menu">
        @guest
            <a href="{{ route('login') }}" class="login-btn">Login</a>
            <a href="{{ route('register') }}" class="register-btn">Register</a>
        @endguest

        @auth
            <span class="username">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        @endauth
    </nav>
</header>

<!-- Konten akan di-load di sini -->
<div id="main-content">
    @yield('content')
</div>

<!-- JS -->
<script src="{{ asset('js/main.js') }}"></script>
<script>
    // Page Transition Animation
    document.addEventListener('DOMContentLoaded', function() {
        // Add transition listeners to all navigation links
        document.querySelectorAll('a:not([href^="#"]):not([target="_blank"])').forEach(link => {
            link.addEventListener('click', function(e) {
                // Skip internal navigation and logout
                if (this.getAttribute('href') === '#' || this.closest('form')) {
                    return;
                }

                const href = this.getAttribute('href');
                
                // Only apply animation for internal navigation
                if (href && !href.startsWith('http') && !href.startsWith('mailto')) {
                    e.preventDefault();
                    
                    document.body.classList.add('page-exit');
                    
                    setTimeout(() => {
                        window.location.href = href;
                    }, 400);
                }
            });
        });

        // Also handle form submissions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                document.body.classList.add('page-exit');
            });
        });
    });
</script>
</body>
</html>
