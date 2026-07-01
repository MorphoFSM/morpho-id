<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Explore {{ ucfirst($kategori_semasa) }} | Morpho.ID</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    @vite(['resources/css/explore.css', 'resources/js/explore.js'])
</head>
<body>
    @include('components.watermark')
    <div id="particles-js"></div>

    @include('components.navbar')

    <header class="page-header">
        <h1 style="text-transform: capitalize;">{{ $kategori_semasa }} Exploration</h1>
    </header>

    <section class="search-filter-section">

        <div class="search-bar-container">
            <input type="text" class="search-input" placeholder="Search Specimen or characteristics...">

            <button type="button" class="btn-dark" data-bs-toggle="modal" data-bs-target="#modalBanding">
                <span class="icon">⚖</span> Compare
            </button>
        </div>

        <div class="filter-tags">
            <button class="tag active filter-btn" data-filter="All">All</button>

            @if(isset($senarai_tag) && count($senarai_tag) > 0)
                @foreach($senarai_tag as $tag)
                    <button class="tag filter-btn" data-filter="{{ strtolower(trim($tag)) }}">{{ trim($tag) }}</button>
                @endforeach
            @else
                <span style="color: #94a3b8; font-size: 0.85rem; padding: 0.5rem;">No anatomical tags recorded yet.</span>
            @endif
        </div>

    </section>

    <main class="specimen-grid">
        @forelse($specimens as $specimen)

            @php
                $ciri_string = is_array($specimen->ciri_ciri) ? implode(', ', $specimen->ciri_ciri) : ($specimen->ciri_ciri ?? '');
            @endphp

            <a href="{{ route('specimen.show', $specimen->id) }}" class="specimen-item" data-tags="{{ strtolower($ciri_string) }}" style="text-decoration: none; color: inherit;">
                <div class="specimen-card" style="cursor: pointer; height: 100%; display: flex; flex-direction: column;">

                    <div class="img-wrapper" style="width: 100%; aspect-ratio: 4/3; overflow: hidden; border-radius: 12px 12px 0 0; background: rgba(0,0,0,0.2); display: flex; justify-content: center; align-items: center;">
                        @if($specimen->gambar && $specimen->gambar !== '-')
                            <img src="{{ $specimen->gambar }}" alt="{{ $specimen->nama_spesimen }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="color: #64748b; font-size: 0.8rem;">No Image</div>
                        @endif
                    </div>

                    <div class="card-content" style="padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column;">
                        <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $specimen->nama_spesimen }}</h3>

                        <p style="font-size: 0.75rem;font-weight: 800; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $specimen->category ? $specimen->category->nama_kategori : '' }}
                        </p>

                        <div class="tags" style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: auto;">
                            @php
                                $tags_array = is_array($specimen->ciri_ciri) ? $specimen->ciri_ciri : explode(',', $ciri_string);
                            @endphp

                            @foreach($tags_array as $tag)
                                @if(!empty(trim($tag)))
                                    <span class="tag-mini" style="background: var(--bg-main); color: var(--text-muted); border: 1px solid var(--border-color); padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 600;">
                                        {{ trim($tag) }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <p style="margin:0;">No specimens available in this category.</p>
            </div>
        @endforelse
    </main>

    <div class="modal fade" id="modalBanding" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 1.5rem; padding: 1.5rem; border: 1px solid var(--border-color); box-shadow: 0 25px 50px -12px rgba(0, 240, 255, 0.25);">

                <div class="modal-header border-0 pb-0 mb-3">
                    <h4 class="modal-title fw-bold" style="color: var(--text-main); letter-spacing: -0.5px;">Compare Specimens</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="opacity: 0.5;"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('specimen.compare') }}" method="GET">

                        <div class="mb-4">
                            <label style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); margin-bottom: 0.6rem; display: block; letter-spacing: 0.5px;">LEFT SPECIMEN (A)</label>
                            <select name="id_a" class="form-select modal-banding-select" style="padding: 0.9rem 1.2rem; border-radius: 0.8rem; font-weight: 600;" required>
                                <option value="" disabled selected>Choose Specimen...</option>
                                @foreach($specimens as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->nama_spesimen }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="text-center mb-4 position-relative">
                            <hr style="border-color: var(--border-color); margin: 0; position: absolute; width: 100%; top: 50%; z-index: 1;">
                            <span style="background: var(--bg-main); color: var(--primary); font-weight: 800; font-size: 0.9rem; padding: 0.4rem 1rem; border-radius: 50px; position: relative; z-index: 2; border: 1px solid var(--border-color);">VS</span>
                        </div>

                        <div class="mb-4">
                            <label style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); margin-bottom: 0.6rem; display: block; letter-spacing: 0.5px;">RIGHT SPECIMEN (B)</label>
                            <select name="id_b" class="form-select modal-banding-select" style="padding: 0.9rem 1.2rem; border-radius: 0.8rem; font-weight: 600;" required>
                                <option value="" disabled selected>Choose Specimen...</option>
                                @foreach($specimens as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->nama_spesimen }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn-analisis" style="width: 100%; padding: 1rem; border: none; border-radius: 0.8rem; font-weight: 700; font-size: 1rem; margin-top: 1rem;">
                            Start Analysis
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.modal-banding-select');

            elements.forEach(function(element) {
                new Choices(element, {
                    searchEnabled: false,
                    itemSelectText: '',
                    shouldSort: false,
                });
            });
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

