import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

const isProduction = process.env.NODE_ENV === 'production';


export default defineConfig({
    resolve: {
        alias: {
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/loading.css',
                'resources/css/modale.css',
                'resources/css/range-input.css',
                'resources/js/app.js',
                'resources/js/loading.js',
                'resources/js/generate-playlist.js',
                'resources/js/multi-range-input.js',
                'resources/js/ziggy.js'
            ],
            refresh: true,
        }),
    ]
});
