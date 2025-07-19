import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Пример: скрипт для страницы оператора
document.addEventListener('DOMContentLoaded', function () {
    console.log('Bootstrap layout loaded');

    // Пример запроса (можно использовать для API дронов)
    // axios.get('/api/drones').then(response => {
    //     console.log(response.data);
    // });
});
