import 'leaflet/dist/leaflet.css';

import 'bootstrap/dist/js/bootstrap.bundle.min'; // Bootstrap JS

import axios from 'axios';
import { createApp } from 'vue';

import ExampleComponent from './components/ExampleComponent.vue';
import DroneMap from './components/DroneMap.vue'; // ✅ добавили

// Глобально доступный axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// --- Vue-приложение  на странице, если он нужен
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('app');
    if (el) {
        const app = createApp({});
        app.component('example-component', ExampleComponent);
        app.component('drone-map', DroneMap);

        app.mount(el);
    } else {
        console.warn('Vue: элемент #app не найден');
    }

    // --- Остальной JS, НЕ внутри Vue
    console.log('Bootstrap + Vue layout loaded');
});
