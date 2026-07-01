<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item_semasa->nama_spesimen }} | Morpho.ID</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/show_specimen.css'])

    <!-- TANAM CSS BREADCRUMB TERUS KAT SINI SUPAYes BOOTSTRAP TAK BOLEH LAWAN -->
    <style>
        .breadcrumb-container {
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            align-items: center;
            margin-bottom: 1rem;
        }

        .breadcrumb-container a.crumb-link {
            color: #00F0FF !important; /* Warna Tema Kuantum */
            text-decoration: none !important; /* Buang garis bawah */
            transition: all 0.2s ease !important;
        }

        /* Custom Scrollbar for Info Box */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(11, 19, 43, 0.3);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 240, 255, 0.4);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 240, 255, 0.8);
        }

        .breadcrumb-container a.crumb-link:hover,
        .breadcrumb-container a.crumb-link:focus,
        .breadcrumb-container a.crumb-link:active {
            color: #00B4D8 !important; /* Warna Teal Terang bila hover */
            text-decoration: none !important; /* <--- Tukar dari underline ke none !important */
        }

        .breadcrumb-container .crumb-separator {
            color: #8AA2B8 !important;
            font-weight: 600;
        }

        .breadcrumb-container .crumb-current {
            color: #FFFFFF !important;
            font-weight: 800;
        }

        /* --- 2. CSS LOADING ANIMATION --- */
        .loader-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(5, 9, 20, 0.85); /* Latar Gelap Lutsinar */
            backdrop-filter: blur(5px);
            display: flex; align-items: center; justify-content: center;
            z-index: 9999;
        }
        .loader-content {
            text-align: center; display: flex; flex-direction: column; align-items: center;
        }
        .spinner {
            width: 50px; height: 50px;
            border: 5px solid rgba(0, 240, 255, 0.2); border-top-color: #00F0FF; /* Warna Kuantum */
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .loader-content p {
            margin-top: 15px; color: #00F0FF; font-weight: 800; font-size: 0.95rem;
            letter-spacing: 1px; animation: pulseText 1.5s ease-in-out infinite;
        }
        @keyframes pulseText {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* OVERRIDE BOOTSTRAP DEFAULTS */
        .text-muted { color: var(--text-muted) !important; }
        .text-dark { color: var(--text-main) !important; }
        .text-black { color: var(--text-main) !important; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div id="particles-js"></div>

    @include('components.navbar')

    <div class="container pb-5 mt-4">

        @php
            $crumbs = [];
            $curr = $item_semasa;

            // 1. Jejak All bapa spesimen sampai ke atas
            while ($curr) {
                array_unshift($crumbs, (object)[
                    'Name' => $curr->nama_spesimen,
                    'url' => route('specimen.show', $curr->id)
                ]);
                $rootSpecimen = $curr;
                $curr = $curr->parent;
            }

            // 2. Add Category (Example: SERANGGA)
            if ($rootSpecimen->category) {
                $cat = $rootSpecimen->category;
                array_unshift($crumbs, (object)[
                    'Name' => $cat->nama_kategori,
                    'url' => '/explore?kategori=' . urlencode($cat->nama_kategori)
                ]);
                // Add Alam/Parent (Example: BIO)
                if ($cat->parent) {
                    array_unshift($crumbs, (object)[
                        'Name' => $cat->parent->nama_kategori,
                        'url' => url('/index')
                    ]);
                } else {
                    $crumbs[0]->url = url('/index');
                }
            }
        @endphp

        <div class="breadcrumb-container ms-1">
            @forelse($crumbs as $crumb)
                @if($loop->last)
                    <span class="crumb-current">{{ strtoupper($crumb->Name) }}</span>
                @else
                    <a href="{{ $crumb->url }}" class="crumb-link">{{ strtoupper($crumb->Name) }}</a>
                    <span class="crumb-separator">/</span>
                @endif
            @empty
                <span class="text-muted">NO CATEGORY</span>
            @endforelse
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: var(--card-bg) !important; backdrop-filter: blur(12px); border: 1px solid var(--border-color) !important;">
                    <div class="specimen-img-wrapper" id="image-container">
                        @if($item_semasa->gambar && $item_semasa->gambar !== '-')
                            <img src="{{ $item_semasa->gambar }}" class="fixed-img" alt="{{ $item_semasa->nama_spesimen }}">
                            <button class="fullscreen-btn" onclick="toggleFullscreen()" title="Enlarge Image">⛶</button>
                            <button class="zoom-toggle-btn" onclick="toggleMagnifier(event)" title="Toggle Magnifier" style="display: none; position: absolute; top: 15px; left: 50%; transform: translateX(-50%); background: rgba(0, 0, 0, 0.6); color: white; border: 1px solid rgba(255,255,255,0.2); border-radius: 30px; padding: 8px 20px; font-weight: bold; font-size: 0.9rem; z-index: 100; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.5); transition: all 0.3s ease;">🔍 Magnifier: OFF</button>
                        @else
                            <div class="d-flex justify-content-center align-items-center" style="min-height: 400px; background: rgba(11, 19, 43, 0.4);"><span class="text-muted">None Gambar</span></div>
                        @endif
                    </div>

                    <div class="position-absolute bottom-0 end-0 p-3">
                        <span class="badge text-dark rounded-pill px-3 py-2 shadow-sm border" style="background: rgba(0, 240, 255, 0.8); border-color: #00F0FF; font-weight: 700; font-size: 0.8rem;">
                            {{ $item_semasa->category ? strtoupper($item_semasa->category->nama_kategori) : 'No Category' }}
                        </span>
                    </div>
                </div>

                <!-- Gallery Section -->
                @if($item_semasa->galeri && count($item_semasa->galeri) > 1)
                <div class="card border-0 shadow-sm rounded-4 mt-3" style="background: var(--card-bg) !important; padding: 15px; border: 1px solid var(--border-color) !important;">
                    <h6 style="color: var(--text-main); font-weight: 700; margin-bottom: 10px; font-size: 0.9rem; letter-spacing: 0.5px;">SPECIMEN GALLERY</h6>
                    <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px;" class="custom-scrollbar">
                        @foreach($item_semasa->galeri as $index => $imgUrl)
                            <img src="{{ $imgUrl }}" onclick="changeMainImage('{{ $imgUrl }}', this)" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid {{ $index === 0 ? 'var(--primary)' : 'transparent' }}; transition: 0.2s; flex-shrink: 0;" class="gallery-thumb" alt="Gallery Image">
                        @endforeach
                    </div>
                </div>
                <script>
                    function changeMainImage(url, el) {
                        document.querySelector('.fixed-img').src = url;
                        document.querySelectorAll('.gallery-thumb').forEach(thumb => thumb.style.borderColor = 'transparent');
                        if (el) el.style.borderColor = 'var(--primary)';
                    }
                </script>
                @endif

                <div class="timestamp-container">
                    <div class="time-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span>Created: {{ $item_semasa->created_at ? $item_semasa->created_at->format('d M Y, h:i A') : 'No Record' }}</span>
                    </div>

                    @if($item_semasa->updated_at && $item_semasa->updated_at != $item_semasa->created_at)
                    <div class="time-item">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                        </svg>
                        <span>Updated: {{ $item_semasa->updated_at->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100" style="background: var(--card-bg) !important; backdrop-filter: blur(12px); border: 1px solid var(--border-color) !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h1 class="fw-bold mb-0" style="color: var(--text-main) !important;">{{ $item_semasa->nama_spesimen }}</h1>
                        @if(Auth::guard('admin')->check())
                            <a href="/admin/specimen/edit/{{ $item_semasa->id }}" class="btn btn-sm transition hover-shadow d-flex align-items-center gap-1" style="border: 1px solid rgba(0, 240, 255, 0.3); color: var(--primary); background: rgba(0, 240, 255, 0.05); border-radius: 50px; padding: 0.4rem 1rem; font-weight: 600; text-decoration: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                Edit Specimen
                            </a>
                        @endif
                    </div>

                    <div class="d-flex gap-2 flex-wrap mb-3 border-bottom pb-4">
                        @php
                            $tags = is_array($item_semasa->ciri_ciri) ? $item_semasa->ciri_ciri : explode(',', $item_semasa->ciri_ciri ?? '');
                        @endphp
                        @forelse($tags as $tag)
                            @if(!empty(trim($tag)))
                                <span class="badge" style="background-color: var(--bg-main); color: var(--primary); border: 1px solid var(--border-color); font-weight: 700; padding: 0.4rem 0.8rem;">
                                    {{ trim($tag) }}
                                </span>
                            @endif
                        @empty
                            <span class="small" style="color: var(--text-muted);">None tag direkodkan.</span>
                        @endforelse
                    </div>

                    <div class="d-flex gap-2 mb-4 flex-wrap">
                        <a href="{{ route('specimen.download.pdf', $item_semasa->id) }}" class="btn btn-sm transition hover-shadow d-flex align-items-center gap-2" style="border: 1px solid rgba(255, 0, 229, 0.3); color: #FF00E5; background: rgba(255, 0, 229, 0.05); border-radius: 8px; padding: 0.5rem 1rem; font-weight: 600; text-decoration: none;" onclick="document.getElementById('global-loader').style.display = 'flex'; setTimeout(() => document.getElementById('global-loader').style.display = 'none', 3000);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            Download PDF
                        </a>
                        <a href="{{ route('specimen.download.zip', $item_semasa->id) }}" class="btn btn-sm transition hover-shadow d-flex align-items-center gap-2" style="border: 1px solid rgba(0, 240, 255, 0.3); color: var(--primary); background: rgba(0, 240, 255, 0.05); border-radius: 8px; padding: 0.5rem 1rem; font-weight: 600; text-decoration: none;" onclick="document.getElementById('global-loader').style.display = 'flex'; setTimeout(() => document.getElementById('global-loader').style.display = 'none', 4000);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Download Image + Info (ZIP)
                        </a>
                    </div>

                    @if(!empty($item_semasa->penerangan))
                        <div class="mt-2 p-4 rounded-3 shadow-sm" style="background: rgba(11, 19, 43, 0.5); border-left: 5px solid var(--accent); border-right: 1px solid var(--border-color); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
                            <h6 class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Specimen Info</h6>
                            <div style="max-height: 200px; overflow-y: auto; padding-right: 10px;" class="custom-scrollbar">
                                <p class="mb-0" style="color: var(--text-main); white-space: pre-wrap; line-height: 1.6;">{{ $item_semasa->penerangan }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 pt-4 border-top">
                        <h6 class="text-muted fw-bold mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Sub-Specimen / Anatomi</h6>

                        <div class="d-flex flex-column gap-2">
                            @forelse($sub_items as $sub)
                                <a href="{{ route('specimen.show', $sub->id) }}" class="text-decoration-none">
                                    <div class="card border rounded-3 transition hover-shadow" style="background: rgba(11, 19, 43, 0.6) !important; border-color: var(--border-color) !important;">
                                        <div class="card-body p-3 d-flex align-items-center">
                                            <span class="badge text-white me-3 px-2 py-1" style="background-color: var(--primary);">Sub</span>
                                            <h6 class="mb-0 fw-bold" style="color: var(--text-main);">{{ $sub->nama_spesimen }}</h6>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center p-3 border rounded-3" style="background-color: var(--bg-main); color: var(--text-muted);">
                                    <small>No sub-specimens recorded</small>
                                </div>
                            @endforelse
                        </div>

                        @if(Auth::guard('admin')->check())
                            <div class="mt-3">
                                <button class="btn w-100 rounded-3 fw-bold py-2 transition" style="border: 2px dashed var(--primary); color: var(--primary); background: transparent;" data-bs-toggle="modal" data-bs-target="#modalAddSub" onmouseover="this.style.background='var(--bg-main)'" onmouseout="this.style.background='transparent'">
                                    + Add Sub-Specimen
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- COMMENTS SECTION -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 p-4" style="background: var(--card-bg) !important; backdrop-filter: blur(12px); border: 1px solid var(--border-color) !important;">
                    <h5 class="fw-bold mb-4" style="color: var(--text-main) !important; letter-spacing: 1px;">DISCUSSION & COMMENTS</h5>

                    <!-- Comment Form -->
                    <form action="{{ route('comment.store', $item_semasa->id) }}" method="POST" class="mb-4 pb-4 border-bottom" style="border-color: rgba(255,255,255,0.05) !important;">
                        @csrf
                        <div class="mb-3">
                            <textarea name="comment" class="form-control rounded-3" rows="3" placeholder="Share your thoughts, ask a question, or provide additional information about this specimen..." style="background: rgba(11, 19, 43, 0.6); color: white; border: 1px solid var(--border-color);" required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-sm rounded-3 fw-bold px-4 py-2 transition hover-shadow" style="background-color: var(--primary); color: #000; border: none; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.2);">
                                Post Comment
                            </button>
                        </div>
                    </form>

                    <!-- Comments List -->
                    <div class="comments-list d-flex flex-column gap-3">
                        @forelse($item_semasa->comments()->orderBy('created_at', 'desc')->get() as $comment)
                            <div class="comment-item p-3 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <strong style="color: var(--primary); font-size: 0.95rem;">{{ $comment->nama }}</strong>
                                        <span class="badge" style="background: rgba(0,240,255,0.1); border: 1px solid rgba(0,240,255,0.2); color: var(--primary); font-size: 0.65rem; letter-spacing: 0.5px;">{{ strtoupper($comment->role) }}</span>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                @php
                                    $currentUser = Auth::guard('admin')->user() ?? Auth::guard('web')->user();
                                    $canManage = $currentUser && (Auth::guard('admin')->check() || $currentUser->name === $comment->nama);
                                @endphp
                                
                                <div id="comment-text-{{ $comment->id }}">
                                    <p class="mb-3" style="color: var(--text-main); font-size: 0.9rem; white-space: pre-wrap; line-height: 1.5;">{{ $comment->comment }}</p>
                                </div>
                                
                                @if($canManage)
                                    <div id="comment-edit-form-{{ $comment->id }}" style="display: none;" class="mb-3">
                                        <form action="{{ route('comment.update', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <textarea name="comment" class="form-control rounded-3 mb-2" rows="3" style="background: rgba(11, 19, 43, 0.6); color: white; border: 1px solid var(--border-color); font-size: 0.9rem;" required>{{ $comment->comment }}</textarea>
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm rounded-3" style="background: transparent; color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1);" onclick="toggleEdit({{ $comment->id }})">Cancel</button>
                                                <button type="submit" class="btn btn-sm rounded-3" style="background-color: var(--primary); color: #000; font-weight: bold;">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <button class="btn btn-sm btn-like d-flex align-items-center gap-2 transition hover-shadow" data-id="{{ $comment->id }}" style="background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1); font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 50px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path></svg>
                                        <span class="like-count fw-bold">{{ $comment->likes }}</span> Like
                                    </button>
                                    
                                    @if($canManage)
                                        <div class="d-flex gap-2">
                                            <button onclick="toggleEdit({{ $comment->id }})" class="btn btn-sm transition hover-shadow" style="background: rgba(0, 240, 255, 0.05); color: var(--primary); border: 1px solid rgba(0, 240, 255, 0.2); font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 50px;">
                                                Edit
                                            </button>
                                            <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" onsubmit="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Are you sure you want to delete this comment?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.submit(); } })" style="margin: 0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm transition hover-shadow" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 50px;">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center p-4 rounded-3" style="background: rgba(0,0,0,0.2); border: 1px dashed rgba(255,255,255,0.1);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 opacity-50"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                <p class="mb-0" style="color: var(--text-muted); font-size: 0.9rem;">No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAddSub" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Sub-Specimen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.specimen.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="specimen_parent_id" value="{{ $item_semasa->id }}">
                        <input type="hidden" name="category_id" value="{{ $item_semasa->category_id }}">

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold small">SUB-SPECIMEN NAME</label>
                            <input type="text" name="nama_spesimen" class="form-control form-control-lg" placeholder="Example: Sel Mikroskopik..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold small">IMAGE</label>
                            <div class="file-upload-box">
                                <input type="file" name="gambar[]" id="file-input" class="d-none" multiple>
                                <label for="file-input" class="w-100 text-center py-3">
                                    <span id="file-label">📁 Choose Image File(s)</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted fw-bold small">DESCRIPTION</label>
                            <textarea name="penerangan" class="form-control form-control-lg" rows="3" placeholder="Tell us a bit about this specimen..."></textarea>
                        </div>

                        <button type="submit" class="btn-simpan w-100 fw-bold">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleFullscreen() {
            const elem = document.getElementById("image-container");
            if (!document.fullscreenElement) {
                elem.requestFullscreen().catch(err => { Swal.fire('Error', err.message, 'error'); });
            } else {
                document.exitFullscreen();
            }
        }

        let isMagnifierActive = false;

        function toggleMagnifier(e) {
            e.stopPropagation();
            const container = document.getElementById("image-container");
            const img = container.querySelector('.fixed-img');
            isMagnifierActive = !isMagnifierActive;

            const btn = container.querySelector('.zoom-toggle-btn');
            if (isMagnifierActive) {
                btn.style.background = "var(--primary)";
                btn.style.color = "#000";
                btn.innerHTML = "🔍 Magnifier: ON";
                img.style.transform = "scale(2.5)";
                container.style.cursor = "crosshair";
            } else {
                btn.style.background = "rgba(0, 0, 0, 0.6)";
                btn.style.color = "white";
                btn.innerHTML = "🔍 Magnifier: OFF";
                img.style.transformOrigin = "center center";
                img.style.transform = "scale(1)";
                container.style.cursor = "default";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById("image-container");
            const img = container.querySelector('.fixed-img');
            const zoomBtn = container.querySelector('.zoom-toggle-btn');

            img.style.transition = "transform 0.3s ease";

            container.addEventListener("mousemove", function(e) {
                if (document.fullscreenElement && isMagnifierActive) {
                    const rect = container.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    
                    img.style.transformOrigin = `${x}% ${y}%`;
                }
            });

            document.addEventListener("fullscreenchange", function() {
                if (!document.fullscreenElement) {
                    // Reset everything when exiting fullscreen
                    isMagnifierActive = false;
                    img.style.transformOrigin = "center center";
                    img.style.transform = "scale(1)";
                    img.style.objectFit = "cover"; // Revert to normal crop
                    container.style.cursor = "default";
                    if (zoomBtn) {
                        zoomBtn.style.display = 'none';
                        zoomBtn.style.background = "rgba(0, 0, 0, 0.6)";
                        zoomBtn.style.color = "white";
                        zoomBtn.innerHTML = "🔍 Magnifier: OFF";
                    }
                } else {
                    // Show full image without cropping when fullscreen
                    img.style.objectFit = "contain";
                    // Show the zoom button when entering fullscreen
                    if (zoomBtn) zoomBtn.style.display = 'block';
                }
            });
            
            const fileInput = document.getElementById('file-input');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const fileName = this.files[0].name;
                    document.getElementById('file-label').textContent = fileName;
                });
            }
        });
    </script>
    <div id="global-loader" class="loader-overlay" style="display: none;">
        <div class="loader-content">
            <span class="spinner"></span>
            <p>System Processing...</p>
        </div>
    </div>

    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                document.getElementById('global-loader').style.display = 'flex';
                const submitBtn = this.querySelector('button[type="submit"]');
                if(submitBtn) {
                    submitBtn.style.opacity = '0.7';
                    submitBtn.style.cursor = 'wait';
                }
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

    <script>
        function toggleEdit(commentId) {
            const textDiv = document.getElementById('comment-text-' + commentId);
            const formDiv = document.getElementById('comment-edit-form-' + commentId);
            
            if (formDiv.style.display === 'none') {
                formDiv.style.display = 'block';
                textDiv.style.display = 'none';
            } else {
                formDiv.style.display = 'none';
                textDiv.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const likeBtns = document.querySelectorAll('.btn-like');
            likeBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent form submission if accidentally inside one
                    const commentId = this.getAttribute('data-id');
                    const btnEl = this;
                    
                    fetch(`/comment/${commentId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            btnEl.querySelector('.like-count').textContent = data.likes;
                            btnEl.style.color = 'var(--primary)';
                            btnEl.style.borderColor = 'var(--primary)';
                            btnEl.style.background = 'rgba(0, 240, 255, 0.1)';
                        }
                    })
                    .catch(err => console.error(err));
                });
            });
        });
    </script>
</body>
</html>

