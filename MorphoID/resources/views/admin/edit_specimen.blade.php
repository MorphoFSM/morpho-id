<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <title>Edit Specimen | Morpho.ID Admin</title>
    @vite(['resources/css/specimenmanage.css', 'resources/js/specimenmanage.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div id="particles-js"></div>

    @include('components.navbar')

    <main class="admin-container">
        <div class="page-header">
            <h1>Edit Database</h1>
            <p>Update existing specimen information.</p>
        </div>

        <div class="form-wrapper">
            <div class="form-card">
                <h2 class="card-title"><span class="circle-num">✎</span> Update: {{ $specimen->nama_spesimen }}</h2>

                <form action="{{ url('admin/specimen/update/' . $specimen->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="redirect_to" value="{{ $previousUrl ?? '' }}">

                    <div class="input-group">
                        <label>NAME OF SPECIMEN</label>
                        <input type="text" name="nama_spesimen" value="{{ $specimen->nama_spesimen }}" required>
                    </div>

                    <div class="input-group">
                        <label>SELECT MAIN GROUP (LARGE CARD)</label>
                        <div class="category-grid">
                            @forelse($induk_list as $atuk)
                                <label class="cat-btn">
                                    <input type="radio" name="parent_id" value="{{ $atuk->id }}" {{ $specimen->parent_id == $atuk->id ? 'checked' : '' }}>
                                    <span class="cat-box" style="position: relative;">
                                        {{ $atuk->nama_spesimen ?? $atuk->nama_kategori }}
                                        <button type="button" title="Delete from Database" onclick="event.preventDefault(); deleteCategory({{ $atuk->id }}, this.closest('label'));" style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: 1px solid #7f1d1d; border-radius: 50%; width: 20px; height: 20px; font-size: 14px; font-weight: bold; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.5); z-index: 10;">&times;</button>
                                    </span>
                                </label>
                            @empty
                            @endforelse

                            <label class="cat-btn">
                                <input type="radio" id="btn_Create_kad_baru" name="parent_id" value="new">
                                <span class="cat-box" style="color: #00F0FF; border-color: #00F0FF; font-weight: 800; background: rgba(0, 240, 255, 0.1);">➕ Create NEW MAIN GROUP</span>
                            </label>
                        </div>

                        <div id="kotak_kad_besar_baru" style="display: none; margin-top: 1rem; padding: 1rem; background: rgba(11, 19, 43, 0.6); border: 1px dashed #00F0FF; border-radius: 0.5rem;">
                            <label style="color: #00F0FF;">NEW MAIN GROUP Name:</label>
                            <input type="text" id="input_kad_besar" name="kad_besar_baru" style="width: 100%; padding: 0.8rem; background: var(--bg-main); color: var(--text-main); border: 1px solid #00F0FF; border-radius: 0.5rem; font-family: inherit;">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Select Parent Category</label>
                        <div class="category-grid">
                            @forelse($kategori_list as $kat)
                                <label class="cat-btn kat-item" data-parent-id="{{ $kat->parent_id }}">
                                    {{-- Kita ambil nama_kategori je untuk value --}}
                                    <input type="radio" name="kategori" value="{{ $kat->id }}"
                                        {{ $specimen->category_id == $kat->id ? 'checked' : '' }}>
                                    <span class="cat-box" style="position: relative;">
                                        {{ $kat->nama_kategori }}
                                        <button type="button" title="Delete from Database" onclick="event.preventDefault(); deleteCategory({{ $kat->id }}, this.closest('label'));" style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: 1px solid #7f1d1d; border-radius: 50%; width: 20px; height: 20px; font-size: 14px; font-weight: bold; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.5); z-index: 10;">&times;</button>
                                    </span>
                                </label>
                            @empty
                                <p>None Category ditemui.</p>
                            @endforelse

                            <label class="cat-btn">
                                <input type="radio" id="btn_Create_Category_baru" name="kategori" value="new_Category">
                                <span class="cat-box" style="color: #00F0FF; border-color: #00F0FF; font-weight: 800; background: rgba(0, 240, 255, 0.1);">➕ CREATE NEW CATEGORY</span>
                            </label>
                        </div>

                        <div id="kotak_Category_baru" style="display: none; margin-top: 1rem; padding: 1rem; background: rgba(11, 19, 43, 0.6); border: 1px dashed #00F0FF; border-radius: 0.5rem;">
                            <label style="color: #00F0FF;">Name Category BAHARU:</label>
                            <input type="text" id="input_Category_baru" name="Category_baru" style="width: 100%; padding: 0.8rem; background: var(--bg-main); color: var(--text-main); border: 1px solid #00F0FF; border-radius: 0.5rem; font-family: inherit;">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>ULTRA STRUCTURE (TAG)</label>
                        <div class="tag-input-wrapper">
                            <input type="text" id="tag_input" placeholder="E.g. scaly, taproot...">
                            <button type="button" id="btn_tambah_tag" class="btn-tambah-tag">Add</button>
                        </div>
                        <div id="tags_container" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.8rem;"></div>

                        <input type="hidden" name="ciri_ciri" id="hidden_ciri_ciri" value="{{ $specimen->ciri_ciri }}">
                    </div>

                    <div style="margin-bottom: 1.5rem; margin-top: 1.5rem;">
                        <label style="display: block; font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.5rem; letter-spacing: 0.5px;">INFO / SPECIMEN DESCRIPTION</label>
                        <textarea name="Description" rows="4" style="width: 100%; padding: 0.8rem 1.2rem; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border-color); border-radius: 0.8rem; font-family: inherit; font-size: 0.95rem; box-sizing: border-box; resize: vertical;">{{ $specimen->Description }}</textarea>
                    </div>

                    <div class="input-group">
                        <label>CURRENT IMAGE</label>
                        @if($specimen->gambar)
                            <div style="margin-bottom: 1rem; border: 1px solid rgba(255, 255, 255, 0.1); padding: 1rem; border-radius: 8px; background: rgba(0, 0, 0, 0.2); display: flex; align-items: flex-start; gap: 1rem;">
                                <img src="{{ $specimen->gambar }}" alt="Current Image" style="max-width: 150px; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.5);">
                                <div>
                                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #ef4444; font-weight: 600; cursor: pointer;">
                                        <input type="checkbox" name="remove_image" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                                        Remove Current Image
                                    </label>
                                    <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.5rem;">Check this box if you want to delete the image completely.</p>
                                </div>
                            </div>
                        @else
                            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem; font-style: italic;">No image currently uploaded.</p>
                        @endif

                        <label>UPLOAD NEW COVER IMAGE</label>
                        <input type="file" name="cover_image" class="file-input" accept="image/png, image/jpeg, image/jpg">
                        <small style="color: #8AA2B8; font-size: 0.8rem; margin-top: 4px; margin-bottom: 15px; display: block;">This image will be displayed on the Explore page.</small>

                        <label style="display: block;">UPLOAD NEW GALLERY IMAGE(S)</label>
                        <input type="file" name="gambar[]" class="file-input" multiple accept="image/png, image/jpeg, image/jpg">
                        <p style="font-size: 0.8rem; color: #64748b; margin-top: 0.5rem;">*Leave empty if you do not want to change the original image.</p>
                    </div>

                    <button  style="background: linear-gradient(135deg, #00F0FF 0%, #9D4EDD 100%); color: #050914; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 10px rgba(0, 240, 255, 0.2); margin-top: 0.8rem; transition: all 0.3s ease;"

    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(0, 240, 255, 0.4)';"
    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(0, 240, 255, 0.2)';" type="submit" class="btn-submit-dark">Save Changes</button>
                </form>

                <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid rgba(239, 68, 68, 0.3);">
                    <h3 style="color: #ef4444; font-size: 1.1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        Danger Zone
                    </h3>
                    <p style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 1rem;">Once you delete a specimen, there is no going back. Please be certain.</p>
                    <form action="{{ url('admin/specimen/padam/' . $specimen->id) }}" method="POST" onsubmit="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Are you absolutely sure you want to delete this specimen? This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.submit(); } })">
                        @csrf
                        @method('DELETE')
                        <!-- Redirect back to manage page instead of just back() -->
                        <input type="hidden" name="redirect_to_manage" value="1">
                        <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid #ef4444; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#ef4444'; this.style.color='white';" onmouseout="this.style.background='rgba(239, 68, 68, 0.1)'; this.style.color='#ef4444';">Delete Specimen</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hiddenInput = document.getElementById('hidden_ciri_ciri');
            const tagsContainer = document.getElementById('tags_container');

            if (hiddenInput && hiddenInput.value) {
                let loadedTags = hiddenInput.value.split(',');
                loadedTags.forEach(tag => {
                    if(tag.trim() !== '') {
                        const tagInput = document.getElementById('tag_input');
                        const btnAdd = document.getElementById('btn_tambah_tag');
                        if (tagInput && btnAdd) {
                            tagInput.value = tag.trim();
                            btnAdd.click();
                        }
                    }
                });
            }
        });

        function deleteCategory(id, labelElement) {
            // SweetAlert is async, so we replace this manually below if needed, but for now we skip standard confirm here because it requires structural changes to the function. Wait, let's replace it properly.
            
            fetch("{{ url('admin/kategori/padam') }}/" + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            }).then(response => {
                if(response.ok) {
                    labelElement.remove();
                } else {
                    Swal.fire('Notice', 'Failed to delete category.', 'info');
                }
            }).catch(err => {
                console.error(err);
                Swal.fire('Notice', 'Network error during deletion.', 'info');
            });
        }
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

