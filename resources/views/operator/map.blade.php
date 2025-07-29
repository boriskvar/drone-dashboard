@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div class="container py-4">
    <h1>Карта дрона (гибридный подход)</h1>

    <div class="mb-3">
        <button id="clear-tracks" class="btn btn-warning btn-sm">Очистить треки</button>
    </div>

    <div id="map" style="height: 500px;"></div>
</div>
@endsection

@section('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const map = L.map('map').setView([50.4501, 30.5234], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markers = {};
    const tracks = {};
    const polylines = {};

    // Плавное перемещение маркера
    function animateMarker(marker, newLatLng) {
        const duration = 1000; // 1 секунда
        const frames = 30;
        const delay = duration / frames;

        const startLatLng = marker.getLatLng();
        let frame = 0;

        const deltaLat = (newLatLng.lat - startLatLng.lat) / frames;
        const deltaLng = (newLatLng.lng - startLatLng.lng) / frames;

        const move = () => {
            if (frame < frames) {
                const lat = startLatLng.lat + deltaLat * frame;
                const lng = startLatLng.lng + deltaLng * frame;
                marker.setLatLng([lat, lng]);
                frame++;
                requestAnimationFrame(move);
            } else {
                marker.setLatLng(newLatLng); // в конце точно установить
            }
        };

        move();
    }

    // Очистка треков
    const clearBtn = document.getElementById('clear-tracks');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            Object.keys(polylines).forEach(id => {
                if (polylines[id]) {
                    map.removeLayer(polylines[id]);
                    polylines[id] = null;
                }
                tracks[id] = [];
            });
        });
    }

    async function fetchCoordinates() {
        try {
            const response = await fetch('/api/coordinates');
            if (!response.ok) throw new Error('Network error');
            const drones = await response.json();

            drones.forEach(drone => {
                // Проверка на наличие координат
                if (!drone.lat || !drone.lng || !Array.isArray(drone.track)) return;

                const latlng = [drone.lat, drone.lng];
                // console.log('Дрон', drone.id, 'координаты', latlng, 'трек:', tracks[drone.id]);
                // Маркер
                if (!markers[drone.id]) {
                    markers[drone.id] = L.marker(latlng).addTo(map).bindPopup(
                        `Дрон #${drone.id}: ${drone.name || ''}`);
                } else {
                    animateMarker(markers[drone.id], L.latLng(latlng));
                }

                // Трек (заменяем из API)
                tracks[drone.id] = drone.track.map(p => [p.lat, p.lng]);

                // Polyline (все треки синие)
                if (!polylines[drone.id]) {
                    polylines[drone.id] = L.polyline(tracks[drone.id], {
                        color: 'blue',
                        weight: 3
                    }).addTo(map);
                } else {
                    polylines[drone.id].setLatLngs(tracks[drone.id]);
                }
            });

        } catch (error) {
            console.error('Ошибка при получении координат:', error);
        }


    }


    fetchCoordinates();
    setInterval(fetchCoordinates, 3000);
});
</script>

@endsection
