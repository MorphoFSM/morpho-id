document.addEventListener('DOMContentLoaded', () => {
    // --- BAHAGIAN 1: PARALLAX SHAPES ---
    const shape1 = document.querySelector('.bg-shape-1');
    const shape2 = document.querySelector('.bg-shape-2');

    let mouseX = 0;
    let mouseY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = (e.clientX / window.innerWidth) * 2 - 1;
        mouseY = (e.clientY / window.innerHeight) * 2 - 1;
    });

    function animateShapes() {
        if (shape1 && shape2) {
            shape1.style.transform = `translate(${mouseX * -50}px, ${mouseY * -50}px)`;
            shape2.style.transform = `translate(${mouseX * 80}px, ${mouseY * 80}px)`;
        }
        requestAnimationFrame(animateShapes);
    }
    animateShapes();

    // --- BAHAGIAN 2: SMOOTH SCROLL ---
    // Kita buat fungsi ni global supaya butang HTML boleh nampak
    window.scrollToSection = function(sectionId) {
        const element = document.getElementById(sectionId);
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        } else {
            console.error("ID '" + sectionId + "' tidak ditemui dalam page.");
        }
    };
});
