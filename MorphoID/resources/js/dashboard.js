document.addEventListener('DOMContentLoaded', () => {
    const headerGreeting = document.querySelector('.dashboard-header h2');

    if (headerGreeting) {
        const hour = new Date().getHours();
        let greeting = 'Selamat Kembali';

        // Logik masa
        if (hour >= 5 && hour < 12) {
            greeting = 'Selamat Pagi';
        } else if (hour >= 12 && hour < 17) {
            greeting = 'Selamat Tengah Hari';
        } else if (hour >= 17 && hour < 20) {
            greeting = 'Selamat Petang';
        } else {
            greeting = 'Selamat Malam';
        }

        // Tulis semula ke dalam HTML
        headerGreeting.innerHTML = `${greeting}, Admin 👋`;
    }
});
