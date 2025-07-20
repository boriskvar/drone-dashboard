import axios from 'axios';
import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue';

// Глобально доступный axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// --- Vue-приложение (работает только с #app)
const app = createApp({});
app.component('example-component', ExampleComponent);
app.mount('#app');

// --- Остальной JS, НЕ внутри Vue
document.addEventListener('DOMContentLoaded', function () {
    console.log('Layout ready');

    // Пример: инициализируем карту вне Vue
    const mapContainer = document.getElementById('map');
    if (mapContainer) {
        console.log('Map container найден, можно инициализировать карту');

        // Например, здесь инициализировать Leaflet
        // const map = L.map('map').setView([50.45, 30.52], 13);
    }
});
