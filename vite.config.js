import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/main.css',
                'resources/js/main.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            // 👇 говорим Vite использовать полную версию Vue
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
});
