
import Alpine from 'alpinejs';
import 'leaflet/dist/leaflet.css'


window.Alpine = Alpine;
Alpine.start();

// resources/js/app.js
// Навигация в Blade
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sidebar a').forEach(link => {
        if (link.href === window.location.href) {
            link.classList.add('nav-item-active');
        }
    });
});
