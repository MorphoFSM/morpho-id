document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const errorMessage = document.getElementById('error-message');
    // Tambah pengesan butang register kat sini
    const submitBtn = document.querySelector('.btn-register'); 

    /* =========================================
       1. LOGIK KAWALAN ADMIN KEY (BARU)
    ========================================= */
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const kotakAdminKey = document.getElementById('kotak_admin_key');
    const inputAdminKey = document.getElementById('input_admin_key');

    if (roleInputs.length > 0) {
        roleInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.id === 'role_Admin') {
                    // Keluar sekali jalan bila select Pentadbir
                    kotakAdminKey.style.display = 'block';
                    inputAdminKey.setAttribute('required', 'required'); // Paksa wajib isi
                    inputAdminKey.focus();
                } else {
                    // Sembunyi balik kalau klik Pelajar semula
                    kotakAdminKey.style.display = 'none';
                    inputAdminKey.removeAttribute('required');
                    inputAdminKey.value = ''; // Cuci semula input
                }
            });
        });
    }

    /* =========================================
       2. SEMAK PADANAN KATA LALUAN & BUTANG SENDING
    ========================================= */
    if (form) {
        form.addEventListener('submit', function(e) {
            if (password.value !== passwordConfirm.value) {
                e.preventDefault(); // Halang borang dari dihantar
                errorMessage.textContent = '* Passwords do not match. Please try again.';
                errorMessage.style.display = 'block';
                passwordConfirm.style.borderColor = '#ef4444';
            } else {
                // Kalau password SAMA, baru borang jalan dan butang tukar "Sending..."
                errorMessage.style.display = 'none';
                passwordConfirm.style.borderColor = '#e2e8f0';

                // Efek butang Sending...
                if (submitBtn) {
                    submitBtn.innerHTML = 'Sending...';
                    submitBtn.style.opacity = '0.7';
                    submitBtn.style.pointerEvents = 'none';
                    submitBtn.style.cursor = 'not-allowed';
                }
            }
        });
    }

    // Hilangkan warna merah bila pengguna mula menaip semula
    if (passwordConfirm) {
        passwordConfirm.addEventListener('input', function() {
            if (errorMessage) errorMessage.style.display = 'none';
            this.style.borderColor = '#059669';
        });
    }
});
