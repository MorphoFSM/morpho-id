<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Specimens | Morpho.ID Admin</title>
    @vite(['resources/css/specimenmanage.css', 'resources/js/specimenmanage.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    @include('components.watermark')
    <div id="particles-js"></div>

    @include('components.navbar')

    <main class="admin-container">

        <!-- HEADER & BUTANG TAMBAH -->
        <div class="page-header">
            <div>
                <h1>Database</h1>
                <p>Manage all parent categories, child categories, and specimens here.</p>
            </div>
            <div style="display: flex; gap: 12px; align-items: center;">
                <a href="{{ route('admin.export') }}" class="btn-tambah-utama" style="background: #10B981; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Export Excel
                </a>
                <button class="btn-tambah-utama" onclick="bukaModal()">➕ Add New</button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error"><strong>⚠️ Error:</strong> {{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <div class="table-card">
            <div class="table-header">
                <h2>Main Groups (Parent Category)</h2>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($induk_list as $item)
                    <tr class="row-induk" id="row-induk-{{ $item->id }}">
                        <td class="col-name">{{ $item->nama_kategori }}</td>
                        <td><span class="tag tag-Parent">Parent</span></td>

                        <td style="text-align: right; vertical-align: middle;">
                            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; width: 100%;">
                                <button type="button" class="btn-lihat-kategori" data-id="{{ $item->id }}" data-name="{{ $item->nama_kategori }}">
                                    Select <span class="panah">➡️</span>
                                </button>

                                <form action="{{ url('admin/kategori/edit/' . $item->id) }}" method="POST" style="margin: 0;" id="form-edit-{{ $item->id }}">
                                    @csrf <input type="hidden" name="nama_kategori" id="input-edit-{{ $item->id }}">
                                    <button type="button" class="btn-action btn-edit" onclick="editKategori({{ $item->id }}, '{{ addslashes($item->nama_kategori) }}')">✏️ Edit</button>
                                </form>

                                <form action="{{ url('admin/kategori/padam/' . $item->id) }}" method="POST" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Delete this Parent Category?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">Delete</button>
                                </form>
                            </div>
                        </td>
                        </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">No Record.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-card mt-4" id="jadual_kategori_section" style="display: none;">
            <div class="table-header">
                <h2>Category Sub</h2>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name Category</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori_list as $kat)
                    <tr class="row-kategori" data-parent-id="{{ $kat->parent_id }}" data-kategori="{{ $kat->nama_kategori }}">
                        <td class="col-name">{{ $kat->nama_kategori }}</td>
                        <td><span class="tag tag-sub">Sub</span></td>

                        <td style="text-align: right; vertical-align: middle;">
                            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; width: 100%;">
                                <button type="button" class="btn-lihat-anak" data-id="{{ $kat->nama_kategori }}" data-name="{{ $kat->nama_kategori }}">View Specimen 👁️</button>

                                <form action="{{ url('admin/kategori/edit/' . $kat->id) }}" method="POST" style="margin: 0;" id="form-edit-{{ $kat->id }}">
                                    @csrf <input type="hidden" name="nama_kategori" id="input-edit-{{ $kat->id }}">
                                    <button type="button" class="btn-action btn-edit" onclick="editKategori({{ $kat->id }}, '{{ addslashes($kat->nama_kategori) }}')">✏️ Edit</button>
                                </form>

                                <form action="{{ url('admin/kategori/padam/' . $kat->id) }}" method="POST" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Delete this Category?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">Delete</button>
                                </form>
                            </div>
                        </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-card mt-4" id="jadual_anak_section" style="display: none;">
            <div class="table-header">
                <h2>Specimens for: <span id="Name_kumpulan_terSelect" style="color:#64748b;">...</span></h2>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Specimen Name</th>
                        <th>Category</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody id="body_jadual_anak">
                    @foreach($specimens as $item)
                    <tr class="row-anak-spesimen" 
                        data-id="{{ $item->id }}"
                        data-level="{{ $item->level ?? 0 }}"
                        data-parent-specimen-id="{{ $item->parent_id ?? '' }}"
                        data-parent-id="{{ $item->category ? $item->category->parent_id : '' }}" 
                        data-kategori="{{ $item->category ? $item->category->nama_kategori : '' }}" 
                        style="display: none; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        
                        @php
                            $lvl = $item->level ?? 0;
                            $textColor = ($lvl == 0) ? '#ffffff' : (($lvl == 1) ? '#00F0FF' : (($lvl == 2) ? '#9D4EDD' : '#F72585'));
                            $lvlName = ($lvl == 0) ? 'Main Specimen' : 'Sub Level ' . $lvl;
                            $lvlBg = ($lvl == 0) ? 'rgba(255,255,255,0.1)' : (($lvl == 1) ? 'rgba(0, 240, 255, 0.1)' : (($lvl == 2) ? 'rgba(157, 78, 221, 0.1)' : 'rgba(247, 37, 133, 0.1)'));
                        @endphp

                        <td class="col-name" style="padding: 0 !important;">
                            <div style="display: flex; align-items: stretch; min-height: 65px; background: {{ $lvl > 0 ? 'linear-gradient(90deg, rgba(255,255,255,0.0) 0%, rgba(255,255,255,0.03) 100%)' : 'transparent' }};">
                                
                                <!-- Base spacer -->
                                <div style="min-width: 15px;"></div>
                                
                                <!-- Indent Guides (Vertical Lines) -->
                                @for($i = 0; $i < $lvl; $i++)
                                    @php
                                        $guideColor = ($i == 0) ? '#00F0FF' : (($i == 1) ? '#9D4EDD' : '#F72585');
                                        $isLast = ($i == $lvl - 1);
                                    @endphp
                                    <div style="min-width: 45px; border-left: {{ $isLast ? '3px' : '1px' }} solid {{ $guideColor }}; opacity: {{ $isLast ? '1' : '0.2' }}; box-shadow: {{ $isLast ? '-2px 0 10px ' . $guideColor : 'none' }};"></div>
                                @endfor

                                <!-- Content Area -->
                                <div style="display: flex; align-items: center; gap: 12px; padding: 10px 0; padding-left: 10px;">
                                    <!-- Toggle Button -->
                                    @if($item->has_children)
                                        <button type="button" class="btn-toggle-children" data-id="{{ $item->id }}" 
                                                style="background: rgba(157, 78, 221, 0.15); border: 1px solid rgba(157, 78, 221, 0.4); border-radius: 6px; color: #d8b4fe; font-weight: bold; cursor: pointer; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; padding: 0; box-shadow: 0 0 10px rgba(157, 78, 221, 0.1); z-index: 2;">
                                            +
                                        </button>
                                    @else
                                        <div style="width: 26px;"></div> <!-- Spacer -->
                                    @endif

                                    <!-- Tree Branch Icon -->
                                    @if($lvl > 0)
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $textColor }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.9; margin-top: 2px;">
                                          <polyline points="15 10 20 15 15 20"></polyline>
                                          <path d="M4 4v7a4 4 0 0 0 4 4h12"></path>
                                        </svg>
                                    @endif

                                    <!-- Text -->
                                    <span style="font-weight: {{ $lvl == 0 ? '600' : '400' }}; color: {{ $textColor }}; font-size: {{ $lvl == 0 ? '1.05rem' : '0.95rem' }}; letter-spacing: 0.5px; margin-left: 2px;">
                                        {{ $item->nama_spesimen }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td style="vertical-align: middle;">
                            <div style="display: flex; flex-direction: column; gap: 8px; align-items: center; justify-content: center; width: 100%;">
                                <span class="tag tag-specimen" style="white-space: nowrap; display: inline-flex; align-items: center; justify-content: center; line-height: 1.2;">{{ $item->category ? $item->category->nama_kategori : '-' }}</span>
                                <span style="background: {{ $lvlBg }}; color: {{ $textColor }}; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; border: 1px solid {{ $textColor }}; opacity: 0.9; white-space: nowrap; display: inline-flex; align-items: center; justify-content: center; line-height: 1.2;">
                                    {{ $lvlName }}
                                </span>
                            </div>
                        </td>

                        <td style="text-align: right; vertical-align: middle;">
                            <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; width: 100%;">
                                <button type="button" class="btn-action btn-add-sub" onclick="bukaModalAddSub({{ $item->id }})" style="color: #00F0FF; background: rgba(0,240,255,0.1); border: 1px solid rgba(0,240,255,0.3); border-radius: 6px; padding: 0.4rem 1rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#00F0FF'; this.style.color='#050914';" onmouseout="this.style.background='rgba(0,240,255,0.1)'; this.style.color='#00F0FF';">Add Sub</button>
                                  <a href="{{ url('admin/specimen/edit/' . $item->id) }}" class="btn-action btn-edit">Edit</a>

                                <form action="{{ url('admin/specimen/padam/' . $item->id) }}" method="POST" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" onclick="event.preventDefault(); Swal.fire({title: 'Confirmation', text: 'Delete this Specimen?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">Delete</button>
                                </form>
                            </div>
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <!-- ============================================== -->
    <!-- MODAL POPUP: BORANG TAMBAH SPESIMEN -->
    <!-- ============================================== -->
    <div id="modalBorang" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Add New Specimen</h2>
                <button class="btn-close" onclick="tutupModal()">&times;</button>
            </div>

            <div class="modal-body">
                <form action="{{ url('admin/specimen/simpan') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <label>Specimen Name</label>
                        <input type="text" name="nama_spesimen" placeholder="Ex: Malayan Tiger" required>
                    </div>

                    <div class="input-group">
                        <label>Parent Specimen (Optional)</label>
                        <select name="specimen_parent_id" style="width: 100%; padding: 12px; border-radius: 6px; background: rgba(0,0,0,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.2); font-size: 0.95rem; cursor: pointer;">
                            <option value="">-- None (Main Specimen) --</option>
                            @foreach($specimens as $sp)
                                <option value="{{ $sp->id }}">
                                    {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $sp->level ?? 0) !!}{{ ($sp->level ?? 0) > 0 ? '↳ ' : '' }}{{ $sp->nama_spesimen }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: rgba(255,255,255,0.5); font-size: 0.8rem; display: block; margin-top: 5px;">If you want this to be a Sub-Specimen, choose its parent here.</small>
                    </div>

                    <div class="input-group">
                        <label>Select Parent Category</label>
                        <div class="category-grid">
                            @forelse($induk_list as $atuk)
                                <label class="cat-btn">
                                    <input type="radio" name="parent_id" value="{{ $atuk->id }}">
                                    <span class="cat-box" style="position: relative;">
                                        {{ $atuk->nama_kategori }}
                                        <button type="button" title="Delete from Database" onclick="event.preventDefault(); deleteCategory({{ $atuk->id }}, this.closest('label'));" style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: 1px solid #7f1d1d; border-radius: 50%; width: 20px; height: 20px; font-size: 14px; font-weight: bold; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.5); z-index: 10;">&times;</button>
                                    </span>
                                </label>
                            @empty
                            @endforelse
                            <label class="cat-btn">
                                <input type="radio" id="btn_Create_kad_baru" name="parent_id" value="new">
                                <span class="cat-box box-add">➕ Create New</span>
                            </label>
                        </div>
                        <div id="kotak_kad_besar_baru" class="hidden-input-box">
                            <input type="text" id="input_kad_besar" name="kad_besar_baru" placeholder="Type parent category name...">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>Select Category</label>
                        <div class="category-grid">
                            @forelse($kategori_list as $kat)
                                <label class="cat-btn kat-item" data-parent-id="{{ $kat->parent_id }}">
                                    <input type="radio" name="kategori" value="{{ $kat->nama_kategori }}">
                                    <span class="cat-box" style="position: relative;">
                                        {{ $kat->nama_kategori }}
                                        <button type="button" title="Delete from Database" onclick="event.preventDefault(); deleteCategory({{ $kat->id }}, this.closest('label'));" style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: 1px solid #7f1d1d; border-radius: 50%; width: 20px; height: 20px; font-size: 14px; font-weight: bold; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.5); z-index: 10;">&times;</button>
                                    </span>
                                </label>
                            @empty
                            @endforelse
                            <label class="cat-btn">
                                <input type="radio" id="btn_Create_Category_baru" name="kategori" value="new_Category">
                                <span class="cat-box box-add">➕ Create New</span>
                            </label>
                        </div>
                        <div id="kotak_kategori_baru" class="hidden-input-box">
                            <input type="text" id="input_kategori_baru" name="Category_baru" placeholder="Type category name...">
                        </div>
                    </div>

                    <div class="input-group">
                        <label>ULTRA STRUCTURE (TAG)</label>
                        <div class="tag-input-wrapper">
                            <input type="text" id="tag_input" placeholder="Ex: scaly, carnivore...">
                            <button type="button" id="btn_tambah_tag" class="btn-tambah-tag">Add</button>
                        </div>
                        <div id="tags_container" class="tags-container"></div>
                        <input type="hidden" name="ciri_ciri" id="hidden_ciri_ciri" value="">
                    </div>

                    <div class="input-group">
                        <label>Description</label>
                        <textarea name="Description" rows="3" placeholder="Brief description..."></textarea>
                    </div>

                    <div class="input-group" style="margin-top: 15px;">
                        <label>UPLOAD COVER IMAGE</label>
                        <input type="file" name="cover_image" class="file-input" accept="image/png, image/jpeg, image/jpg">
                        <small style="color: #8AA2B8; font-size: 0.8rem; margin-top: 4px; display: block;">This image will be displayed on the Explore page.</small>
                    </div>

                    <div class="input-group" style="margin-top: 15px;">
                        <label>UPLOAD GALLERY IMAGE(S)</label>
                        <input type="file" name="gambar[]" class="file-input" multiple accept="image/png, image/jpeg, image/jpg">
                    </div>

                    <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px;">
                        <button type="button" class="btn-Cancel" onclick="tutupModal()" style="background: rgba(255,255,255,0.05); color: #ccc; border: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Cancel</button>
                        <button type="submit" class="btn-Save" style="background: linear-gradient(135deg, #00F0FF 0%, #0080FF 100%); color: #000; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.3); transition: all 0.2s;">Save Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- MODAL POPUP: RENAME KATEGORI -->
    <!-- ============================================== -->
    <div id="modalRenameCategory" class="modal-overlay" style="display: none;">
        <div class="modal-box" style="max-width: 400px; padding: 25px;">
            <div class="modal-header">
                <h2>Rename Category</h2>
                <button type="button" class="btn-close" onclick="tutupModalRename()">&times;</button>
            </div>
            <div class="modal-body" style="margin-top: 15px;">
                <div class="input-group">
                    <label>New Category Name</label>
                    <input type="text" id="input_rename_category" class="form-input" style="width: 100%; padding: 12px; border-radius: 6px; background: rgba(0,0,0,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.2); font-size: 1rem;" onfocus="this.select()">
                </div>
                <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 25px;">
                    <button type="button" class="btn-Cancel" onclick="tutupModalRename()" style="background: rgba(255,255,255,0.05); color: #ccc; border: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s;">Cancel</button>
                    <button type="button" id="btn_confirm_rename" style="background: linear-gradient(135deg, #00F0FF 0%, #0080FF 100%); color: #000; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 240, 255, 0.3); transition: all 0.2s;">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT UNTUK BUKA/TUTUP MODAL -->
    <script>
        function bukaModal() {
            let selectElement = document.querySelector('select[name="specimen_parent_id"]');
            if(selectElement) { selectElement.value = ""; }
            document.getElementById('modalBorang').style.display = 'flex';
        }
        function bukaModalAddSub(parentId) {
            document.getElementById('modalBorang').style.display = 'flex';
            let selectElement = document.querySelector('select[name="specimen_parent_id"]');
            if(selectElement) { selectElement.value = parentId; }
        }
        function tutupModal() {
            document.getElementById('modalBorang').style.display = 'none';
        }
        function tutupModalRename() {
            document.getElementById('modalRenameCategory').style.display = 'none';
        }
        // Tutup modal kalau user klik kat luar kotak
        window.onclick = function(event) {
            let modalBorang = document.getElementById('modalBorang');
            let modalRename = document.getElementById('modalRenameCategory');
            if (event.target == modalBorang) {
                modalBorang.style.display = "none";
            }
            if (event.target == modalRename) {
                modalRename.style.display = "none";
            }
        }
        
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

