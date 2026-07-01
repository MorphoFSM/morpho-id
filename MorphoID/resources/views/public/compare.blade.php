<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Perbandingan: {{ $specimenA->nama_spesimen }} VS {{ $specimenB->nama_spesimen }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/compare.css', 'resources/js/compare.js'])
</head>
<body>
    <div id="particles-js"></div>

    <header class="compare-header">
        <div class="logo">Morpho<span>.</span>ID <div class="badge-mode">Comparative Analysis Mode</div></div>
        <a href="/explore?kategori={{ urlencode($specimenA->category ? $specimenA->category->nama_kategori : '') }}" class="btn-close-compare">Close Comparison ✕</a>
    </header>

    <main class="compare-area">
        <div class="vs-badge">VS</div>

        <div class="panel panel-left">
            @if($specimenA->gambar && $specimenA->gambar !== '-')
                <img src="{{ $specimenA->gambar }}" class="specimen-img" alt="{{ $specimenA->nama_spesimen }}">
            @else
                <div style="height: 350px; background: var(--bg-main); display: flex; align-items: center; justify-content: center; border-radius: 1rem; border: 1px dashed var(--border-color); margin-bottom: 1.5rem;">
                    <span style="color: var(--text-muted); font-weight: 600;">No Image Available</span>
                </div>
            @endif

            @php
                $crumbsA = [];
                $currA = $specimenA;

                // 1. Jejak All bapa spesimen sampai ke atas
                while ($currA) {
                    array_unshift($crumbsA, (object)[
                        'Name' => $currA->nama_spesimen,
                        'url' => '/compare?id_a=' . $currA->id . '&id_b=' . $specimenB->id
                    ]);
                    $rootSpecimenA = $currA;
                    $currA = $currA->parent;
                }

                // 2. Add Category dan Alam di pangkal
                if ($rootSpecimenA->category) {
                    $catA = $rootSpecimenA->category;
                    array_unshift($crumbsA, (object)[
                        'Name' => $catA->nama_kategori,
                        'url' => '/explore?kategori=' . urlencode($catA->nama_kategori)
                    ]);
                    if ($catA->parent) {
                        array_unshift($crumbsA, (object)[
                            'Name' => $catA->parent->nama_kategori,
                            'url' => '/explore?kategori=' . urlencode($catA->parent->nama_kategori)
                        ]);
                    }
                }
            @endphp

            <div class="breadcrumb-container">
                @forelse($crumbsA as $crumb)
                    <a href="{{ $crumb->url }}" class="crumb-link">{{ strtoupper($crumb->Name) }}</a>
                    @if(!$loop->last) <span class="crumb-separator">/</span> @endif
                @empty
                    <span class="cat-label">NO CATEGORY AVAILABLE</span>
                @endforelse
            </div>

            <h1 class="specimen-name">{{ $specimenA->nama_spesimen }}</h1>

            <div class="compare-section-title">Penerangan</div>
            <p class="specimen-desc">{{ $specimenA->penerangan ?? 'No description available.' }}</p>

            <div class="compare-section-title">Ciri-Ciri (Tags)</div>
            <div style="margin-bottom: 2rem;">
                @php
                    $tagsA = is_array($specimenA->ciri_ciri) ? $specimenA->ciri_ciri : explode(',', $specimenA->ciri_ciri ?? '');
                @endphp
                @forelse($tagsA as $tag)
                    @if(!empty(trim($tag)))
                        <span class="tag-compare">{{ trim($tag) }}</span>
                    @endif
                @empty
                    <span style="color: var(--text-muted); font-size: 0.85rem;">No tags recorded.</span>
                @endforelse
            </div>

            <div class="compare-section-title">Sub-Specimen / Anatomi</div>
            <div class="sub-spesimen-container">
                @forelse($subA as $sub)
                    <a href="/compare?id_a={{ $sub->id }}&id_b={{ $specimenB->id }}" class="sub-item-link">
                        <div class="sub-item-card">
                            <span class="sub-badge">Sub</span>
                            <span class="sub-name">{{ $sub->nama_spesimen }}</span>
                        </div>
                    </a>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.85rem;">No sub-specimens recorded.</p>
                @endforelse
            </div>
        </div>

        <div class="panel panel-right">
            @if($specimenB->gambar && $specimenB->gambar !== '-')
                <img src="{{ $specimenB->gambar }}" class="specimen-img" alt="{{ $specimenB->nama_spesimen }}">
            @else
                <div style="height: 350px; background: var(--card-bg); display: flex; align-items: center; justify-content: center; border-radius: 1rem; border: 1px dashed var(--border-color); margin-bottom: 1.5rem;">
                    <span style="color: var(--text-muted); font-weight: 600;">No Image Available</span>
                </div>
            @endif

            @php
                $crumbsB = [];
                $currB = $specimenB;

                // 1. Jejak All bapa spesimen sampai ke atas
                while ($currB) {
                    array_unshift($crumbsB, (object)[
                        'Name' => $currB->nama_spesimen,
                        'url' => '/compare?id_a=' . $specimenA->id . '&id_b=' . $currB->id
                    ]);
                    $rootSpecimenB = $currB;
                    $currB = $currB->parent;
                }

                // 2. Add Category dan Alam di pangkal
                if ($rootSpecimenB->category) {
                    $catB = $rootSpecimenB->category;
                    array_unshift($crumbsB, (object)[
                        'Name' => $catB->nama_kategori,
                        'url' => '/explore?kategori=' . urlencode($catB->nama_kategori)
                    ]);
                    if ($catB->parent) {
                        array_unshift($crumbsB, (object)[
                            'Name' => $catB->parent->nama_kategori,
                            'url' => '/explore?kategori=' . urlencode($catB->parent->nama_kategori)
                        ]);
                    }
                }
            @endphp

            <div class="breadcrumb-container">
                @forelse($crumbsB as $crumb)
                    <a href="{{ $crumb->url }}" class="crumb-link">{{ strtoupper($crumb->Name) }}</a>
                    @if(!$loop->last) <span class="crumb-separator">/</span> @endif
                @empty
                    <span class="cat-label">NO CATEGORY AVAILABLE</span>
                @endforelse
            </div>

            <h1 class="specimen-name">{{ $specimenB->nama_spesimen }}</h1>

            <div class="compare-section-title">Penerangan</div>
            <p class="specimen-desc">{{ $specimenB->penerangan ?? 'No description available.' }}</p>

            <div class="compare-section-title">Ciri-Ciri (Tags)</div>
            <div style="margin-bottom: 2rem;">
                @php
                    $tagsB = is_array($specimenB->ciri_ciri) ? $specimenB->ciri_ciri : explode(',', $specimenB->ciri_ciri ?? '');
                @endphp
                @forelse($tagsB as $tag)
                    @if(!empty(trim($tag)))
                        <span class="tag-compare">{{ trim($tag) }}</span>
                    @endif
                @empty
                    <span style="color: var(--text-muted); font-size: 0.85rem;">No tags recorded.</span>
                @endforelse
            </div>

            <div class="compare-section-title">Sub-Specimen / Anatomi</div>
            <div class="sub-spesimen-container">
                @forelse($subB as $sub)
                    <a href="/compare?id_a={{ $specimenA->id }}&id_b={{ $sub->id }}" class="sub-item-link">
                        <div class="sub-item-card">
                            <span class="sub-badge">Sub</span>
                            <span class="sub-name">{{ $sub->nama_spesimen }}</span>
                        </div>
                    </a>
                @empty
                    <p style="color: var(--text-muted); font-size: 0.85rem;">No sub-specimens recorded.</p>
                @endforelse
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

