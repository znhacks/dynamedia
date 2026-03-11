<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamedia - When Creations Come to Life</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="landing-body">
    <div class="landing-container">
        <a href="{{ route('gallery') }}" class="landing-text">DYNAMEDIA</a>
        <p class="landing-subtitle">When Creations Come to Life</p>
    </div>

    <div class="landing-hint">Click to enter →</div>

    <script>
        // Page Transition Animation for Landing Page
        document.addEventListener('DOMContentLoaded', function() {
            const link = document.querySelector('.landing-text');
            
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.body.classList.add('page-exit');
                
                setTimeout(() => {
                    window.location.href = this.getAttribute('href');
                }, 400);
            });
        });
    </script>
</body>
</html>
