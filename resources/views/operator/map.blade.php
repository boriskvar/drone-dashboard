@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div class="container py-4">
    <h1>Карта дрона</h1>
    <div id="map" style="height: 500px;"></div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const map = L.map('map').setView([50.4501, 30.5234], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
            , attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const droneMarkers = {};
        const trackPolylines = {};
        const targetMarkers = {};

        function createDroneIcon() {
            return L.icon({
                iconUrl: '/images/drone-arrow.svg'
                , iconSize: [30, 30]
                , iconAnchor: [15, 15]
                , popupAnchor: [0, -15]
            });
        }

        function createTargetIcon() {
            return L.icon({
                iconUrl: '/images/target-icon.svg'
                , iconSize: [24, 24]
                , iconAnchor: [12, 12]
            });
        }

        function extractTargetLatLng(drone) {
            if (drone.target && drone.target.latitude != null && drone.target.longitude != null) {
                const lat = parseFloat(drone.target.latitude);
                const lng = parseFloat(drone.target.longitude);
                if (!Number.isNaN(lat) && !Number.isNaN(lng)) return [lat, lng];
            }
            return null;
        }

        async function fetchCoordinates() {
            try {
                const response = await fetch('/api/simulate-flight-data');
                if (!response.ok) throw new Error('Network error');

                const drones = await response.json();

                drones.forEach(drone => {
                    const latlng = [drone.latitude, drone.longitude];

                    // Маркер дрона
                    if (!droneMarkers[drone.id]) {
                        droneMarkers[drone.id] = L.marker(latlng, {
                            icon: createDroneIcon()
                            , rotationAngle: drone.heading || 0
                            , rotationOrigin: 'center center'
                        }).addTo(map);

                        droneMarkers[drone.id].bindPopup(`
                        <strong>Дрон #${drone.id}</strong><br>
                        Имя: ${drone.name}<br>
                        Широта: ${drone.latitude}<br>
                        Долгота: ${drone.longitude}<br>
                        Высота: ${drone.altitude ?? '—'} м<br>
                        Скорость: ${drone.speed ?? '—'} км/ч<br>
                        Курс: ${drone.heading ?? '—'}°<br>
                        Обновлено: ${drone.updated_at}
                    `);
                    } else {
                        droneMarkers[drone.id].setLatLng(latlng);
                        droneMarkers[drone.id].setRotationAngle(drone.heading || 0);
                        droneMarkers[drone.id].getPopup().setContent(`
                        <strong>Дрон #${drone.id}</strong><br>
                        Имя: ${drone.name}<br>
                        Широта: ${drone.latitude}<br>
                        Долгота: ${drone.longitude}<br>
                        Высота: ${drone.altitude ?? '—'} м<br>
                        Скорость: ${drone.speed ?? '—'} км/ч<br>
                        Курс: ${drone.heading ?? '—'}°<br>
                        Обновлено: ${drone.updated_at}
                    `);
                    }

                    // Линия трека
                    const trackLatLngs = drone.track.map(p => [parseFloat(p.latitude), parseFloat(p.longitude)]);
                    if (!trackPolylines[drone.id]) {
                        trackPolylines[drone.id] = L.polyline(trackLatLngs, {
                            color: 'blue'
                            , weight: 3
                        }).addTo(map);
                    } else {
                        trackPolylines[drone.id].setLatLngs(trackLatLngs);
                    }

                    // Цель
                    const targetLatLng = extractTargetLatLng(drone);
                    if (targetLatLng) {
                        if (!targetMarkers[drone.id]) {
                            targetMarkers[drone.id] = L.marker(targetLatLng, {
                                icon: createTargetIcon()
                            }).addTo(map);
                        } else {
                            targetMarkers[drone.id].setLatLng(targetLatLng);
                        }
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
