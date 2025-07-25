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

    // Цвет по id
    function getColorById(id) {
        const colors = ['red', 'blue', 'green', 'orange', 'purple', 'black', 'brown', 'darkcyan', 'magenta'];
        return colors[id % colors.length];
    }

    // Очистка треков
    document.getElementById('clear-tracks').addEventListener('click', () => {
        Object.keys(polylines).forEach(id => {
            if (polylines[id]) {
                map.removeLayer(polylines[id]);
                polylines[id] = null;
            }
            tracks[id] = [];
        });
    });

    async function fetchCoordinates() {
        try {
            const response = await fetch('/api/coordinates');
            if (!response.ok) throw new Error('Network error');
            const drones = await response.json();

            drones.forEach(drone => {
                const latlng = [drone.lat, drone.lng];

                // Маркер
                if (!markers[drone.id]) {
                    markers[drone.id] = L.marker(latlng).addTo(map).bindPopup(`Дрон #${drone.id}`);
                } else {
                    markers[drone.id].setLatLng(latlng);
                }

                // Трек
                if (!tracks[drone.id]) tracks[drone.id] = [];
                tracks[drone.id].push(latlng);
                if (tracks[drone.id].length > 20) tracks[drone.id].shift();

                // Polyline
                if (!polylines[drone.id]) {
                    polylines[drone.id] = L.polyline(tracks[drone.id], {
                        color: getColorById(drone.id),
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
