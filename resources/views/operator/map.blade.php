@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div class="container py-4">
    <h1>Карта дрона (упрощённый вариант)</h1>

    <div id="map" style="height: 500px;"></div>
</div>
@endsection


@section('scripts')
<!-- === Подключение Leaflet  CSS и JS=== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Leaflet Rotated Marker плагин, чтобы поворот иконки работал -->
<script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // === Создаём карту ===
        const map = L.map('map').setView([50.4501, 30.5234], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Хранилища маркеров и треков
        const markers = {};
        const polylines = {};

        // Создание иконки дрона
        function createDroneIcon() {
            return L.icon({
                iconUrl: '/images/drone-arrow.svg',
                iconSize: [30, 30],
                iconAnchor: [15, 15],
                popupAnchor: [0, -15],
                className: 'leaflet-drone-icon'
            });
        }

        // Анимация перемещения маркера
        function animateMarker(marker, newLatLng) {
            marker.setLatLng(newLatLng);
        }

        // Получение и отрисовка данных с API
        async function fetchCoordinates() {
            try {
                const response = await fetch('/api/simulate-flight-data');
                if (!response.ok) throw new Error('Network error');

                const drones = await response.json();

                drones.forEach(drone => {
                    const latlng = [drone.latitude, drone.longitude];

                    // Создаём или обновляем маркер
                    if (!markers[drone.id]) {
                        const marker = L.marker(latlng, {
                            icon: createDroneIcon(),
                            rotationAngle: drone.heading || 0, // поворот при создании
                            rotationOrigin: 'center center'
                        }).addTo(map);

                        marker.bindPopup(`
                        <strong>Дрон #${drone.id}:</strong> ${drone.name}<br>
                        Широта: ${drone.latitude}<br>
                        Долгота: ${drone.longitude}<br>
                        Высота: ${drone.altitude ?? '—'} м<br>
                        Скорость: ${drone.speed ?? '—'} км/ч<br>
                        Курс: ${drone.heading ?? '—'}°<br>
                        Обновлено: ${drone.updated_at}
                    `);

                        markers[drone.id] = marker;
                    } else {
                        markers[drone.id].getPopup().setContent(`
                            <strong>Дрон #${drone.id}:</strong> ${drone.name}<br>
                            Широта: ${drone.latitude}<br>
                            Долгота: ${drone.longitude}<br>
                            Высота: ${drone.altitude ?? '—'} м<br>
                            Скорость: ${drone.speed ?? '—'} км/ч<br>
                            Курс: ${drone.heading ?? '—'}°<br>
                            Обновлено: ${drone.updated_at}
                    `);

                        animateMarker(markers[drone.id], L.latLng(latlng));

                        // Обновляем поворот
                        markers[drone.id].setRotationAngle(drone.heading || 0);
                    }

                    // Обновляем или создаём трек
                    const trackLatLngs = drone.track.map(p => [parseFloat(p.latitude), parseFloat(p
                        .longitude)]);

                    if (!polylines[drone.id]) {
                        polylines[drone.id] = L.polyline(trackLatLngs, {
                            color: 'blue',
                            weight: 3
                        }).addTo(map);
                    } else {
                        polylines[drone.id].setLatLngs(trackLatLngs);
                    }
                });

            } catch (error) {
                console.error('Ошибка при получении координат:', error);
            }
        }

        // Запуск загрузки данных и автообновление
        fetchCoordinates();
        setInterval(fetchCoordinates, 3000);

    });
</script>
@endsection