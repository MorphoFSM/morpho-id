<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Register Account | Morpho.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/registration.css', 'resources/js/registration.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <div id="particles-js"></div>

            <div class="register-card">
                    <div class="header">
                        <div class="logo-wrapper">
                            <div class="mini-logo"></div>
                            <div class="logo">Morpho<span>.</span>ID</div>
                        </div>
                    <p class="subtitle">Register a new account for repository access</p>

                    <form action="/registration" method="POST" id="registerForm">
                        @if ($errors->any())
                <div style="background-color: #FEE2E2; border: 1px solid #EF4444; color: #B91C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @csrf
                @if(session('error'))
                    <div style="background-color: #FEE2E2; border: 1px solid #EF4444; color: #B91C1C; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: bold; text-align: center;">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="input-group">
                    <label>FULL NAME</label>
                    <input type="text" name="name" placeholder="Full Name" required>
                </div>
                <div class="input-group">
                    <label>E-MAIL ADDRESS</label>
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>

                <div class="input-group">
                    <label>IDENTITY CARD NUMBER (without hyphens)</label>
                    <input type="text" name="userid" placeholder="010203045566" maxlength="12" required>
                </div>

                <div class="input-group">
                    <label>INSTITUTION</label>
                    <input type="text" name="institusi" placeholder="Example: UPSI / SMK Aminuddin Baki" required>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label>PASSWORD</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" placeholder="••••••••" required>
                            <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>CONFIRM PASSWORD</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirm" placeholder="••••••••" required>
                            <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('password_confirm', this)"></i>
                        </div>
                    </div>
                </div>

                <div class="input-group">
                    <label>ACCESS ROLES</label>
                    <div class="role-selector">
                        <label class="role-btn"><input type="radio" name="role" value="User" checked><span class="role-box">User</span></label>
                        <label class="role-btn"><input type="radio" name="role" value="Administrator" id="role_Admin"><span class="role-box">Administrator</span></label>
                    </div>
                </div>

                <button type="submit" class="btn-register sonar-pulse">Register Account</button>
            </form>



            <div class="footer">
                already have an account? <a href="/login" class="login-link">Log In here</a>
            </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        const submitBtn = document.querySelector('.btn-register');

        /* =========================================
           2. Check PASSWORD & BUTANG SENDING
        ========================================= */
        if (form && submitBtn) {
            // Kita tukar cara detect butang sikit supaYes lebih agresif
            form.addEventListener('submit', function(e) {

                // Check password sama ke tak
                if (password.value !== passwordConfirm.value) {
                    e.preventDefault(); // STOP form dari dihantar ke Laravel
                    Swal.fire('Notice', 'Please ensure both passwords match!', 'info'); // Kita pakai alert je lagi senang nampak
                    passwordConfirm.style.borderColor = '#ef4444';
                    return false;
                }

                // Kalau password SAMA, terus tukar butang!
                submitBtn.innerHTML = 'Sending...';
                submitBtn.style.opacity = '0.7';
                submitBtn.style.pointerEvents = 'none';
                submitBtn.style.cursor = 'not-allowed';
            });
        }
    });

    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>



    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 60, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": ["#00F0FF", "#9D4EDD"] }, 
                "shape": { "type": "circle" },
                "opacity": { "value": 0.8, "random": true },
                "size": { "value": 4, "random": true },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#00F0FF", 
                    "opacity": 0.3,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 1.5,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                }
            },
            "interactivity": {
                "detect_on": "window",
                "events": {
                    "onhover": { "enable": true, "mode": "repulse" }, 
                    "onclick": { "enable": true, "mode": "push" }, 
                    "resize": true
                },
                "modes": {
                    "repulse": { "distance": 100, "duration": 0.4 },
                    "push": { "particles_nb": 4 }
                }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>

