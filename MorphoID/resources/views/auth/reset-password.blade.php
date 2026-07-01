<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Create New Password | Morpho.ID.</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/registration.css'])
</head>
<body>
    <div id="particles-js"></div>
    @include('components.watermark')

    <div class="register-card">
        <div class="header">
            <div class="logo-wrapper">
                <img src="https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/natey/MorphoID%20Logo.png" alt="Logo" class="mini-logo">
                <div class="logo">Morpho<span>.</span>ID</div>
            </div>
            <p class="subtitle">Enter your new secure password</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST">
            
            @if ($errors->any())
                <div style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.5); color: #EF4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.5); color: #EF4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: bold; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif

            @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="input-group">
                <label>E-MAIL ADDRESS</label>
                <input type="email" value="{{ $email }}" disabled style="background-color: #f3f4f6; color: #6b7280;">
            </div>

            <div class="input-row">
                <div class="input-group">
                    <label>NEW PASSWORD</label>
                    <input type="password" name="password" placeholder="••••••••" required autofocus>
                </div>
                <div class="input-group">
                    <label>CONFIRM NEW PASSWORD</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-register">Reset Password</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 60, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": ["#00F0FF", "#9D4EDD"] }, 
                "shape": { "type": "circle" },
                "opacity": { "value": 0.8, "random": true },
                "size": { "value": 4, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#00F0FF", "opacity": 0.3, "width": 1 },
                "move": { "enable": true, "speed": 1.5, "direction": "none", "random": true, "out_mode": "out" }
            },
            "interactivity": {
                "detect_on": "window",
                "events": { "onhover": { "enable": true, "mode": "repulse" }, "onclick": { "enable": true, "mode": "push" }, "resize": true },
                "modes": { "repulse": { "distance": 100, "duration": 0.4 }, "push": { "particles_nb": 4 } }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>

