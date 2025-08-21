import 'leaflet/dist/leaflet.css';

import 'bootstrap/dist/js/bootstrap.bundle.min'; // Bootstrap JS

import axios from 'axios';
import { createApp } from 'vue';

import ExampleComponent from './components/ExampleComponent.vue';
import DroneMap from './components/DroneMap.vue'; // ✅ добавили
import SimulationMap from './components/SimulationMap.vue';

// Глобально доступный axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// --- Vue-приложение  на странице, если он нужен (Vue создаётся только если есть <div id="app">)
// Компоненты ExampleComponent и DroneMap зарегистрированы глобально
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('app');
    if (el) {
        const app = createApp({});
        app.component('example-component', ExampleComponent);
        app.component('drone-map', DroneMap);
        app.component('simulation-map', SimulationMap);

        app.mount(el);
    } else {
        console.warn('Vue: элемент #app не найден');
    }

    // --- Остальной JS, НЕ внутри Vue (работает вне Vue)
    console.log('Bootstrap + Vue layout loaded');
});
