<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Morpho.ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/login.css'])
</head>
<body>
    <div id="particles-js"></div>

    <div class="login-card">
        <div class="header">
            <div class="logo-wrapper">
                <div class="mini-logo"></div>
                <div class="logo">Morpho<span>.</span>ID</div>
            </div>
            <div class="subtitle">Biological Exploration Repository System</div>
        </div>

        @if(session('success'))
            <div style="background-color: rgba(157, 78, 221, 0.1); color: #9D4EDD; border: 1px solid #34d399; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 0.85rem; font-weight: 700;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background-color: #fee2e2; color: #dc2626; border: 1px solid #f87171; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 0.85rem; font-weight: 700;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background-color: #fee2e2; color: #dc2626; border: 1px solid #f87171; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; font-weight: 600;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="input-group">
                <label>USER ID</label>
                <input type="text" name="userid" placeholder="Example: 031211030747" value="{{ old('userid') }}" required>
            </div>

            <div class="input-group">
                <label>PASSWORD</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="••••••••" required>
                    <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                </div>

                <a href="/forgot-password" class="forgot-password-link">Forgot password?</a>
            </div>

            <div class="input-group">
                <label>ACCESS ROLES</label>
                <div class="role-selector">
                    <label class="role-btn">
                        <input type="radio" name="role" id="role_User" value="User" {{ old('role', 'User') == 'User' ? 'checked' : '' }}>
                        <span class="role-box">User</span>
                    </label>
                    <label class="role-btn">
                        <input type="radio" name="role" id="role_Admin" value="Administrator" {{ old('role') == 'Administrator' ? 'checked' : '' }}>
                        <span class="role-box">Administrator</span>
                    </label>
                </div>
            </div>

            <div id="kotak_admin_key" class="input-group" style="display: none; margin-top: 15px;">
                <label>ADMINISTRATION KEY (First-time login only)</label>
                <div class="password-wrapper">
                    <input type="password" name="admin_key" id="input_admin_key" placeholder="Leave blank if you've logged in before">
                    <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('input_admin_key', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-login sonar-pulse">Login</button>
        </form>

        <div class="footer">&copy; 2026 Morpho.ID System. All Rights Reserved.</div>
    </div>

    <div id="global-loader" class="loader-overlay" style="display: none;">
        <div class="loader-content">
            <span class="spinner"></span>
            <p>Loading...</p>
        </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const kotakAdminKey = document.getElementById('kotak_admin_key');
            const inputAdminKey = document.getElementById('input_admin_key');

            if (roleInputs.length > 0) {
                // Initialize on load
                if (document.getElementById('role_Admin').checked) {
                    kotakAdminKey.style.display = 'block';
                }

                // Add change listeners
                roleInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        if (this.id === 'role_Admin') {
                            kotakAdminKey.style.display = 'block';
                            inputAdminKey.focus();
                        } else {
                            kotakAdminKey.style.display = 'none';
                            inputAdminKey.value = '';
                        }
                    });
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
</body>
</html>

