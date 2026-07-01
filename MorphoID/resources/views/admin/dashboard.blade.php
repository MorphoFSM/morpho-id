<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Morpho.ID</title>

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>
<body>
    @include('components.watermark')
    <div id="particles-js"></div>
    @include('components.navbar')

    <main class="dashboard-container">
        <header class="dashboard-header">
            <h2>Welcome Back, Admin 👋</h2>
            <p>Monitor and manage the laboratory database from this control center.</p>
        </header>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">🧬</div>
                <h3>Total Specimens</h3>
                <p class="stat-value">24</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">🗂️</div>
                <h3>Active Categories</h3>
                <p class="stat-value">4</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⚡</div>
                <h3>System Status</h3>
                <p class="stat-value status-online">Online</p>
            </div>
        </div>

        <div class="action-menu">
            <h3>Action Shortcuts</h3>
            <div class="action-grid">
                <a href="/admin/manage" class="action-box primary-action">
                    <h4>➕ Manage Database</h4>
                    <p>Add, update, or delete specimen records.</p>
                </a>

                <a href="{{ route('admin.role_requests') }}" class="action-box primary-action" style="position: relative;">
                    <h4>👤 Role Requests</h4>
                    <p>Approve or reject user admin role requests.</p>
                    @php
                        $pendingRequestsCount = \App\Models\RoleRequest::where('status', 'pending')->count();
                    @endphp
                    @if($pendingRequestsCount > 0)
                        <span style="position: absolute; top: 10px; right: 10px; background-color: #f50057; color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold; box-shadow: 0 0 10px rgba(245, 0, 87, 0.5);">{{ $pendingRequestsCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.audit') }}" class="action-box primary-action">
                    <h4>📋 Activity Logs</h4>
                    <p>Track all admin actions, creations, and deletions.</p>
                </a>

                <a href="/" class="action-box secondary-action">
                    <h4>👀 View Public Catalog</h4>
                    <p>Check the public interface for external users.</p>
                </a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
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

