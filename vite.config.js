import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/homepage.css', 
                'resources/js/homepage.js',
                'resources/css/becas/index.css',
                'resources/js/becas/index.js', 
                'resources/css/calendario.css', 
                'resources/js/calendario.js',
                'resources/css/navbar.css',
                'resources/js/navbar.js',
                'resources/css/footer.css',
                'resources/css/settings.css',
                'resources/js/settings.js',
                'resources/css/foro/index.css',
                'resources/css/foro/create.css',
                'resources/css/rol-flow.css',
                'resources/js/rolflow.js',
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