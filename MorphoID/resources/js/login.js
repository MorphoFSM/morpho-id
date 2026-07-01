document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.querySelector('.login-form');
    const submitBtn = document.querySelector('.btn-submit');

    if (loginForm) {
        loginForm.addEventListener('submit', () => {
            // Tukar teks dan gaya butang bila borang dihantar
            submitBtn.innerHTML = 'Mengesahkan...';
            submitBtn.style.opacity = '0.8';
            submitBtn.style.cursor = 'wait';
            submitBtn.style.pointerEvents = 'none'; // Elak double click
        });
    }
});
