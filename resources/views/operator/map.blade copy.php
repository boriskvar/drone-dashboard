@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div class="container py-4">
    <h1>Карта дрона (гибридный подход)</h1>
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

        const markers = {}; // Маркеры по id дрона
        const tracks = {}; // Массивы точек трека по id дрона
        const polylines = {}; // Polyline объекты по id дрона

        async function fetchCoordinates() {
            try {
                const response = await fetch('/api/coordinates');
                if (!response.ok) throw new Error('Network error');
                const drones = await response.json();

                drones.forEach(drone => {
                    const latlng = [drone.lat, drone.lng];

                    // Создаем маркер, если его нет
                    if (!markers[drone.id]) {
                        markers[drone.id] = L.marker(latlng).addTo(map).bindPopup(`Дрон #${drone.id}`);
                    } else {
                        markers[drone.id].setLatLng(latlng);
                    }

                    // Инициализируем трек, если нужно
                    if (!tracks[drone.id]) {
                        tracks[drone.id] = [];
                    }
                    // Добавляем новую точку в трек (можно добавить проверку на дубли)
                    tracks[drone.id].push(latlng);

                    // Оставляем только последние 20 точек
                    if (tracks[drone.id].length > 20) {
                        tracks[drone.id].shift();
                    }

                    // Создаем или обновляем polyline для трека
                    if (!polylines[drone.id]) {
                        polylines[drone.id] = L.polyline(tracks[drone.id], {
                            color: 'blue'
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
