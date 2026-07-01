document.addEventListener("DOMContentLoaded", function() {
    // Cari form dan butang submit
    const form = document.querySelector('form');
    const submitBtn = document.querySelector('.btn-register');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            // Tukar teks butang supaya user tahu sistem tengah proses
            submitBtn.innerHTML = 'Sending...';
            
            // Kelabukan sikit butang tu
            submitBtn.style.opacity = '0.7';
            
            // Matikan fungsi klik supaya tak boleh tekan dua kali
            submitBtn.style.pointerEvents = 'none';
        });
    }
});