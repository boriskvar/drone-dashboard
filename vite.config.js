import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',    // Tailwind (для Breeze)
                'resources/js/app.js',     // Breeze JS
                'resources/css/main.css', // Ваш Main CSS
                'resources/js/main.js'    // Main JS
            ],
            refresh: true,
        }),
        vue(), // ← Добавить сюда
    ],
});
