<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Laboratory Sample Catalog | Morpho.ID</title>
    @vite(['resources/css/index.css'])
</head>
<body>
    @include('components.watermark')
    <div id="particles-js"></div>

    @include('components.navbar')

    @if(session('success'))
        <div class="alert-box alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-box alert-error">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            {{ session('error') }}
        </div>
    @endif

    @php
        if (Auth::guard('admin')->check()) {
            $pengguna = Auth::guard('admin')->user();
            $peranan = 'System Admin';
        } elseif (Auth::check()) {
            $pengguna = Auth::user();
            $peranan = 'User';
        } else {
            $pengguna = null;
            $peranan = 'Guest';
        }
    @endphp

    <div class="hero-banner">
        <div class="banner-bg"></div>

        <div class="hero-content">
            <span class="welcome-text">Welcome</span>
            <h1 class="greeting">Hi, {{ $pengguna ? strtoupper($pengguna->name) : 'GUEST' }} 👋</h1>
            <p class="role-badge">{{ $peranan }}</p>
            <p class="subtitle">MORPHOLOGY IDENTIFICATION DATABASE SYSTEM</p>
        </div>

        <div style="background: rgba(11, 19, 43, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(0, 240, 255, 0.2); border-radius: 16px; padding: 2rem 1.5rem; text-align: center; box-shadow: 0 10px 25px rgba(0, 240, 255, 0.1); width: 100%; max-width: 350px;">



            <p style="color: #00F0FF; font-size: 5rem; font-weight: 900; margin: 0; line-height: 1; text-shadow: 0 0 15px rgba(0, 240, 255, 0.6);">
                {{ \App\Models\LoginLog::count() }}
            </p>
            
            <h3 style="color: #8AA2B8; font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; margin: 5px 0 0 0;">
                Total Visitors
            </h3>

        </div>
    </div>

    <header class="hero-section">
        <p>A centralized classification system for microstructure analysis, material composition, and specimen physiological data.</p>
    </header>

    <main class="katalog-container">

        @forelse($kategori_induk as $atuk)
            <div class="katalog-card">

                <div class="card-header">
                    @if(str_contains(strtolower($atuk->nama_kategori), 'chem'))
                        <div class="icon-box icon-chem">🧪</div>
                    @else
                        <div class="icon-box icon-bio">🧬</div>
                    @endif

                    <div class="header-text">
                        <h2>{{ $atuk->nama_kategori }}</h2>
                    </div>
                </div>
                
                <div class="item-list">

                    @foreach($atuk->children as $bapak)
                        <div style="display: flex; gap: 10px; align-items: stretch; margin-bottom: 10px; width: 100%;">
                            <a href="/explore?kategori={{ urlencode($bapak->nama_kategori) }}" class="Category-item" >
                                {{ strtoupper($bapak->nama_kategori) }}
                            </a>

                        </div>
                    @endforeach

                    @foreach($atuk->specimens as $isi)
                        <div style="display: flex; gap: 10px; align-items: stretch; margin-bottom: 10px; width: 100%;">
                            <a href="/specimen/{{ $isi->id }}" class="Category-item" >
                                {{ strtoupper($isi->nama_spesimen) }}
                            </a>

                        </div>
                    @endforeach

                    @if($atuk->children->isEmpty() && $atuk->specimens->isEmpty())
                        <span style="color: var(--text-muted); font-size: 0.85rem; padding: 1rem 0;">
                            No sub-categories or specimens available.
                        </span>
                    @endif
                </div>

            </div>
        @empty
            <div class="text-center" style="color: var(--text-muted); width: 100%; margin-top: 2rem;">
                <p>The database is empty. Please register Main Categories in the Specimen Management section.</p>
            </div>
        @endforelse

    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.0/vanilla-tilt.min.js"></script>
    <script>
        // Inisialisasi Vanilla Tilt pada kad katalog (Kesan Magnetik 3D Parallax)
        VanillaTilt.init(document.querySelectorAll(".katalog-card"), {
            max: 5,
            speed: 400,
            glare: true,
            "max-glare": 0.15,
        });
    </script>
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

