import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 'resources/js/app.js',
                'resources/css/intro.css', 'resources/js/intro.js',
                'resources/css/index.css', 'resources/js/index.js',
                'resources/css/explore.css', 'resources/js/explore.js',
                'resources/css/login.css', 'resources/js/login.js',
                'resources/css/registration.css', 'resources/js/registration.js',
                'resources/css/show_specimen.css',
                'resources/css/specimenmanage.css', 'resources/js/specimenmanage.js',
                'resources/css/dashboard.css', 'resources/js/dashboard.js',
                'resources/css/compare.css', 'resources/js/compare.js',
                'resources/css/admin_visit.css', 'resources/js/forgot-password.js',
                'resources/css/profile.css',
                'resources/css/role_requests.css'
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
