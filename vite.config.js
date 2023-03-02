import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        https: {
            key: 'C:/laragon/etc/ssl/laragon.key',
            cert: "C:/laragon/etc/ssl/laragon.crt",
        },
        host: 'spw-mailmart.test',
        hmr: {
            host: 'spw-mailmart.test',
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
