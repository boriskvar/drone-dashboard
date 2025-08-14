@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div class="container py-4">
    <h1>Карта дрона</h1>

    <div class="d-flex gap-2 mb-3">
        <button id="set-target-btn" class="btn btn-danger btn-sm">Назначить цель</button>
    </div>

    <div id="map" style="height: 500px;"></div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>

<div class="d-flex gap-2 mb-3">
    <button id="set-target-btn" class="btn btn-danger btn-sm">Назначить цель</button>
</div>
<div id="map" style="height: 500px;"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const map = L.map('map').setView([50.4501, 30.5234], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
            , attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Хранилища
        const droneMarkers = {};
        const trackPolylines = {};
        const targetMarkers = {};
        let activeDroneId = null;
        let selectedTargetLatLng = null;

        function createDroneIcon() {
            return L.icon({
                iconUrl: '/images/drone-arrow.svg'
                , iconSize: [30, 30]
                , iconAnchor: [15, 15]
            });
        }

        function createTargetIcon() {
            return L.icon({
                iconUrl: '/images/target-icon.svg'
                , iconSize: [30, 30]
                , iconAnchor: [15, 15]
            });
        }

        function extractTargetLatLng(drone) {
            if (drone.target && drone.target.latitude && drone.target.longitude) {
                return [parseFloat(drone.target.latitude), parseFloat(drone.target.longitude)];
            }
            return null;
        }

        // --- Загрузка координат всех дронов ---
        async function fetchCoordinates() {
            try {
                const response = await fetch('/api/flight-data'); // возвращает массив всех дронов
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
                            })
                            .addTo(map)
                            .bindPopup(`<strong>Дрон #${drone.id}</strong><br>Имя: ${drone.name}<br>Курс: ${drone.heading ?? '—'}°`);

                        // Клик на маркер — выбираем дрон
                        droneMarkers[drone.id].on('click', () => {
                            activeDroneId = drone.id;
                            alert(`Выбран дрон #${drone.id}. Теперь клик на карту назначит цель.`);
                        });

                    } else {
                        droneMarkers[drone.id].setLatLng(latlng);
                        droneMarkers[drone.id].setRotationAngle(drone.heading || 0);
                    }

                    // Трек
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

        // --- Клик на карте для назначения цели ---
        map.on('click', function(e) {
            if (!activeDroneId) {
                alert('Сначала выберите дрон (клик по маркеру)');
                return;
            }
            selectedTargetLatLng = e.latlng;

            // Удаляем старый маркер цели
            if (targetMarkers[activeDroneId]) {
                map.removeLayer(targetMarkers[activeDroneId]);
            }

            // Добавляем новый маркер цели
            targetMarkers[activeDroneId] = L.marker(selectedTargetLatLng, {
                    icon: createTargetIcon()
                })
                .addTo(map)
                .bindPopup(`Новая цель для дрона #${activeDroneId}`).openPopup();

            // Отправка на сервер
            fetch(`/api/drones/${activeDroneId}/target`, {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
                , body: JSON.stringify({
                    latitude: selectedTargetLatLng.lat
                    , longitude: selectedTargetLatLng.lng
                })
            }).then(res => {
                if (!res.ok) console.error("Ошибка при установке цели");
            });
        });

        // --- Кнопка «Назначить цель» просто предупреждает, если дрон не выбран ---
        document.getElementById('set-target-btn').addEventListener('click', () => {
            if (!activeDroneId) {
                alert('Сначала выберите дрон (клик по маркеру)');
            } else {
                alert('Теперь клик на карту назначит цель выбранному дрону.');
            }
        });

        // --- Первичная загрузка и автообновление ---
        fetchCoordinates();
        setInterval(fetchCoordinates, 3000);

    });
</script>

@endsection