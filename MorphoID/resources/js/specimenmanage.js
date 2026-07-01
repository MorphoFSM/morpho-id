document.addEventListener('DOMContentLoaded', function() {

    /* =========================================
       1. FUNGSI TAG CIRI-CIRI ANATOMI
    ========================================= */
    const tagInput = document.getElementById('tag_input');
    const btnTambah = document.getElementById('btn_tambah_tag');
    const tagsContainer = document.getElementById('tags_container');
    const hiddenInput = document.getElementById('hidden_ciri_ciri');
    const form = document.querySelector('form');

    let tagsArray = [];

    if (form) {
        form.addEventListener('submit', function(e) {
            updateHiddenInput();
            console.log("Borang sedang dihantar ke server...");
        });
    }

    // Fungsi untuk tambah tag
    function addTag() {
        const tagText = tagInput.value.trim();

        if (tagText !== '' && !tagsArray.includes(tagText)) {
            tagsArray.push(tagText);
            updateTagsUI();
            updateHiddenInput();
            tagInput.value = '';
        }
    }

    // Fungsi untuk kemas kini paparan tag kat skrin
    function updateTagsUI() {
        tagsContainer.innerHTML = '';

        tagsArray.forEach((tag, index) => {
            const tagElement = document.createElement('span');
            tagElement.style.cssText = 'background: #e2e8f0; color: #0f172a; padding: 0.3rem 0.8rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;';

            tagElement.innerHTML = `
                ${tag}
                <span style="cursor: pointer; color: #ef4444;" onclick="removeTag(${index})">✖</span>
            `;
            tagsContainer.appendChild(tagElement);
        });
    }

    // Fungsi untuk padam tag bila pangkah ditekan
    window.removeTag = function(index) {
        tagsArray.splice(index, 1);
        updateTagsUI();
        updateHiddenInput();
    };

    // Fungsi untuk kemas kini hidden input (untuk hantar ke database)
    function updateHiddenInput() {
        if(hiddenInput) hiddenInput.value = tagsArray.join(', ');
    }

    if(btnTambah && tagInput) {
        btnTambah.addEventListener('click', addTag);
        tagInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag();
            }
        });
    }

    /* =========================================
       2. FUNGSI ALAM KATEGORI BAHARU (MAGIK ENTER)
    ========================================= */
    const kotakKategori = document.getElementById('kotak_kategori_baru');
    const inputKategori = document.getElementById('input_kategori_baru');

    if (kotakKategori && inputKategori) {
        const gridKategori = document.querySelector('input[name="kategori"]').closest('.category-grid');

        document.addEventListener('change', function(e) {
            if (e.target.name === 'kategori') {
                if (e.target.id === 'btn_Create_Category_baru') {
                    kotakKategori.style.display = 'block';
                    inputKategori.focus();
                } else {
                    kotakKategori.style.display = 'none';
                    inputKategori.value = '';
                }
            }
        });

        inputKategori.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = this.value.trim();

                if (val !== '') {
                    const existing = gridKategori.querySelector(`input[value="${val}"]`);
                    if (!existing) {
                        const newBtn = document.createElement('label');
                        newBtn.className = 'cat-btn custom-added-kategori kat-item';
                        
                        // Cari mana-mana Parent Category yang sedang terpilih
                        const selectedParent = document.querySelector('input[name="parent_id"]:checked');
                        const curParentId = selectedParent ? selectedParent.value : '';
                        newBtn.setAttribute('data-parent-id', curParentId);
                        
                        newBtn.innerHTML = `
                            <input type="radio" name="kategori" value="${val}">
                            <span class="cat-box" style="position: relative;">
                                ${val.toUpperCase()}
                                <button type="button" title="Remove" onclick="event.preventDefault(); this.closest('label').remove();" style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: 1px solid #7f1d1d; border-radius: 50%; width: 20px; height: 20px; font-size: 14px; font-weight: bold; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.5); z-index: 10;">&times;</button>
                            </span>
                        `;
                        gridKategori.insertBefore(newBtn, gridKategori.lastElementChild);
                    }
                    kotakKategori.style.display = 'none';
                    this.value = '';
                    document.getElementById('btn_Create_Category_baru').checked = false;
                }
            }
        });
    }

    /* =========================================
       3. FUNGSI KAD BESAR BAHARU (MAGIK ENTER)
    ========================================= */
    const kotakKadBesar = document.getElementById('kotak_kad_besar_baru');
    const inputKadBesar = document.getElementById('input_kad_besar');

    if (kotakKadBesar && inputKadBesar) {
        const gridKadBesar = document.querySelector('input[name="parent_id"]').closest('.category-grid');

        document.addEventListener('change', function(e) {
            if (e.target.name === 'parent_id') {
                if (e.target.id === 'btn_Create_kad_baru') {
                    kotakKadBesar.style.display = 'block';
                    inputKadBesar.focus();
                } else {
                    if (!e.target.closest('.custom-added-kad')) {
                        kotakKadBesar.style.display = 'none';
                        inputKadBesar.value = '';
                    }
                }
                
                // DRILL-DOWN LOGIC: Hide/show child categories based on parent_id
                const selectedParentId = e.target.value;
                const childCatItems = document.querySelectorAll('.kat-item');
                childCatItems.forEach(item => {
                    const itemParentId = item.getAttribute('data-parent-id');
                    // Tunjuk jika parent_id sama, ATAU jika child tu takde parent_id lagi (safety)
                    if (itemParentId == selectedParentId || !itemParentId) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                        // Nyahtanda (uncheck) jika ia sedang dipilih tapi disembunyikan
                        const radio = item.querySelector('input[type="radio"]');
                        if (radio) radio.checked = false;
                    }
                });
            }
        });

        // Tapis Child Category pada mula-mula page di load (terutamanya untuk page Edit)
        setTimeout(() => {
            const checkedParent = document.querySelector('input[name="parent_id"]:checked');
            if (checkedParent) {
                checkedParent.dispatchEvent(new Event('change', { bubbles: true }));
            } else {
                // Sembunyikan semua child jika tiada parent dipilih
                const childCatItems = document.querySelectorAll('.kat-item');
                childCatItems.forEach(item => {
                    item.style.display = 'none';
                });
            }
        }, 100);

        inputKadBesar.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = this.value.trim();

                if (val !== '') {
                    // Check if already exists to prevent duplicate buttons
                    const existing = gridKadBesar.querySelector(`input[value="NEW_${val}"]`);
                    if (!existing) {
                        const newBtn = document.createElement('label');
                        newBtn.className = 'cat-btn custom-added-kad';
                        newBtn.innerHTML = `
                            <input type="radio" name="parent_id" value="NEW_${val}">
                            <span class="cat-box" style="position: relative;">
                                ${val.toUpperCase()}
                                <button type="button" title="Remove" onclick="event.preventDefault(); this.closest('label').remove();" style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border: 1px solid #7f1d1d; border-radius: 50%; width: 20px; height: 20px; font-size: 14px; font-weight: bold; line-height: 1; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.5); z-index: 10;">&times;</button>
                            </span>
                        `;
                        gridKadBesar.insertBefore(newBtn, gridKadBesar.lastElementChild);
                    }
                    kotakKadBesar.style.display = 'none';
                    this.value = '';
                    document.getElementById('btn_Create_kad_baru').checked = false;
                }
            }
        });
    }

    /* =========================================
       4. FUNGSI DRILL-DOWN (LOGIK BARU!)
    ========================================= */
    let currentParentId = null;

    const btnsLihatKategori = document.querySelectorAll('.btn-lihat-kategori');
    const sectionKategori = document.getElementById('jadual_kategori_section');
    const rowsKategori = document.querySelectorAll('.row-kategori');
    const rowsInduk = document.querySelectorAll('.row-induk');

    const btnsLihatAnak = document.querySelectorAll('.btn-lihat-anak');
    const sectionAnak = document.getElementById('jadual_anak_section');
    const rowsAnak = document.querySelectorAll('.row-anak-spesimen');
    const namaKumpulanLabel = document.getElementById('nama_kumpulan_terpilih');

    // TAHAP 1: BILA TEKAN KAD BESAR -> KELUAR KATEGORI YANG BERKAITAN SAHAJA
    if (btnsLihatKategori.length > 0) {
        btnsLihatKategori.forEach(btn => {
            btn.addEventListener('click', function() {
                // Ambil ID Kad Besar (Atuk)
                currentParentId = this.getAttribute('data-id');

                rowsInduk.forEach(r => r.classList.remove('row-induk-aktif'));
                this.closest('tr').classList.add('row-induk-aktif');

                // Logik Baru: Terus padankan parent_id Kategori dengan ID Atuk
                rowsKategori.forEach(row => {
                    const katParentId = row.getAttribute('data-parent-id');

                    if (katParentId === currentParentId) {
                        row.style.display = ''; // Tunjuk
                    } else {
                        row.style.display = 'none'; // Sorok
                    }
                });

                if (sectionAnak) sectionAnak.style.display = 'none';

                if (sectionKategori) {
                    sectionKategori.style.display = 'block';
                    setTimeout(() => {
                        sectionKategori.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            });
        });
    }

    // TAHAP 2: BILA TEKAN KATEGORI -> KELUAR SPESIMEN
    if (btnsLihatAnak.length > 0) {
        btnsLihatAnak.forEach(btn => {
            btn.addEventListener('click', function() {
                const kategoriPilih = this.getAttribute('data-id'); // Nama Kategori
                const namaKategori = this.getAttribute('data-name');

                rowsKategori.forEach(r => r.classList.remove('row-induk-aktif'));
                this.closest('tr').classList.add('row-induk-aktif');

                if (namaKumpulanLabel) {
                    namaKumpulanLabel.innerText = namaKategori;
                }

                // Tapis spesimen berdasarkan Atuk & Bapak
                rowsAnak.forEach(row => {
                    const matchParent = row.getAttribute('data-parent-id') == currentParentId;
                    const matchKategori = row.getAttribute('data-kategori') == kategoriPilih;
                    const isLevel0 = row.getAttribute('data-level') == '0';

                    if (matchParent && matchKategori && isLevel0) {
                        row.style.display = ''; // Tunjuk level 0 sahaja
                    } else {
                        row.style.display = 'none'; // Sembunyi yang lain
                    }
                    
                    // Reset butang toggle kepada '+'
                    const toggleBtn = row.querySelector('.btn-toggle-children');
                    if (toggleBtn) toggleBtn.innerText = '+';
                });

                sectionAnak.style.display = 'block'; // Tunjuk jadual
                setTimeout(() => {
                    sectionAnak.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            });
        });
    }

    // TAHAP 3: TOGGLE ANAK SPESIMEN (HIERARCHY DROPDOWN)
    document.querySelectorAll('.btn-toggle-children').forEach(btn => {
        btn.addEventListener('click', function() {
            const specimenId = this.getAttribute('data-id');
            const isExpanded = this.innerText === '-';
            
            this.innerText = isExpanded ? '+' : '-';
            
            // Function untuk sembunyikan semua anak/cucu di bawahnya
            const hideChildrenRecursively = (parentId) => {
                rowsAnak.forEach(row => {
                    if (row.getAttribute('data-parent-specimen-id') == parentId) {
                        row.style.display = 'none';
                        const toggleBtn = row.querySelector('.btn-toggle-children');
                        if (toggleBtn) toggleBtn.innerText = '+';
                        hideChildrenRecursively(row.getAttribute('data-id'));
                    }
                });
            };

            if (isExpanded) {
                // Sembunyikan anak (dan cucu) secara rekursif
                hideChildrenRecursively(specimenId);
            } else {
                // Tunjuk anak-anak secara langsung (immediate children)
                rowsAnak.forEach(row => {
                    if (row.getAttribute('data-parent-specimen-id') == specimenId) {
                        row.style.display = '';
                    }
                });
            }
        });
    });

                /* =========================================
            5. FUNGSI EDIT KATEGORI (CUSTOM MODAL)
            ========================================= */
            window.editKategori = function(id, namaLama) {
                const modal = document.getElementById('modalRenameCategory');
                const input = document.getElementById('input_rename_category');
                const btnConfirm = document.getElementById('btn_confirm_rename');
                
                if(modal && input && btnConfirm) {
                    input.value = namaLama;
                    modal.style.display = 'flex';
                    input.focus();
                    
                    // Cleanup previous event listeners to avoid multiple submissions
                    const newBtnConfirm = btnConfirm.cloneNode(true);
                    btnConfirm.parentNode.replaceChild(newBtnConfirm, btnConfirm);
                    
                    newBtnConfirm.addEventListener('click', function() {
                        const namaBaru = input.value;
                        if (namaBaru != null && namaBaru.trim() !== "") {
                            document.getElementById('input-edit-' + id).value = namaBaru;
                            document.getElementById('form-edit-' + id).submit();
                        }
                    });

                    // Add enter key support
                    input.onkeypress = function(e) {
                        if (e.key === 'Enter') {
                            newBtnConfirm.click();
                        }
                    };
                } else {
                    // Fallback
                    let namaBaru = prompt("Rename category below:", namaLama);
                    if (namaBaru != null && namaBaru.trim() !== "") {
                        document.getElementById('input-edit-' + id).value = namaBaru;
                        document.getElementById('form-edit-' + id).submit();
                    }
                }
            };

});
