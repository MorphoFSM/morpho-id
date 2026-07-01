document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const specimenItems = document.querySelectorAll('.specimen-item');
    const searchInput = document.querySelector('.search-input');

    // Fungsi Utama: Tapis berdasarkan Search (Taip) + Tags (Klik)
    function jalankanTapisan() {
        const kataCarian = searchInput ? searchInput.value.toLowerCase() : '';
        const activeTags = Array.from(document.querySelectorAll('.filter-btn.active'))
                                .map(b => b.getAttribute('data-filter'));

        specimenItems.forEach(item => {
            const namaSpesimen = item.querySelector('h3').innerText.toLowerCase();
            const itemTags = item.getAttribute('data-tags') || '';

            // Tukar string "karnivor, mamalia" jadi array sebenar ['karnivor', 'mamalia']
            const itemTagsArray = itemTags.split(',').map(t => t.trim());

            // 1. Syarat Search (Tengok nama atau tag)
            const lulusCarian = namaSpesimen.includes(kataCarian) || itemTags.includes(kataCarian);

            // 2. Syarat Tag (Logik AND - Mesti ngam 100% ejaan tag tu)
            let lulusTag = true;
            if (!activeTags.includes('all')) {
                lulusTag = activeTags.every(tag => itemTagsArray.includes(tag));
            }

            // 3. Keputusan
            if (lulusCarian && lulusTag) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Event 1: Bila user menaip kat kotak search
    if (searchInput) {
        searchInput.addEventListener('input', jalankanTapisan);
    }

    // Event 2: Bila user klik butang filter tag
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filterValue = this.getAttribute('data-filter');

            if (filterValue === 'all') {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            } else {
                const btnSemua = document.querySelector('.filter-btn[data-filter="semua"]');
                if(btnSemua) btnSemua.classList.remove('active');

                this.classList.toggle('active');

                const activeCount = document.querySelectorAll('.filter-btn.active').length;
                if (activeCount === 0 && btnSemua) {
                    btnSemua.classList.add('active');
                }
            }

            // Laksanakan tapisan selepas butang ditekan
            jalankanTapisan();
        });
    });
});
