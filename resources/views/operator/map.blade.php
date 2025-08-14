@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
    <div class="container py-4">
        <h1>Карта дрона</h1>

        <div class="d-flex gap-2 mb-3">
            <button id="assignTargetBtn" class="btn btn-danger btn-sm">Назначить цель</button>
        </div>

        <div id="map" style="height: 500px;"></div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>



    <script>
        // === Инициализация карты ===
        let map = L.map('map').setView([50.4501, 30.5234], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let selectedDroneId = null; // выбранный дрон
        let pendingTargetLatLng = null; // координаты цели после клика по карте
        let updatingTarget = false; // флаг обновления цели

        const droneMarkers = {};
        const targetMarkers = {};
        const trackPolylines = {};

        // === Иконки ===
        function createDroneIcon() {
            return L.icon({
                iconUrl: '/images/drone-arrow.svg'
                , iconSize: [40, 40]
                , iconAnchor: [20, 20]
            });
        }

        function createTargetIcon() {
            return L.icon({
                iconUrl: '/images/target-icon.svg',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
        }

        // === Получение координат всех дронов ===
        async function fetchCoordinates() {
            try {
                const response = await fetch('/api/drones/flight-data');
                if (!response.ok) throw new Error('Network error');

                const drones = await response.json();

                drones.forEach(drone => {
                    const latlng = [drone.latitude, drone.longitude];

                    // --- Маркер дрона ---
                    const popupContent = `
                                                        <strong>Дрон #${drone.id}:</strong> ${drone.name || ''}<br>
                                                        Широта: ${drone.latitude}<br>
                                                        Долгота: ${drone.longitude}<br>
                                                        Высота: ${drone.altitude ?? '—'} м<br>
                                                        Скорость: ${drone.speed ?? '—'} км/ч<br>
                                                        Курс: ${drone.heading ?? '—'}°<br>
                                                        Обновлено: ${drone.updated_at}
                                                    `;

                    if (!droneMarkers[drone.id]) {
                        droneMarkers[drone.id] = L.marker(latlng, {
                            icon: createDroneIcon()
                            , rotationAngle: drone.heading || 0
                            , rotationOrigin: 'center center'
                        }).addTo(map).bindPopup(popupContent);

                        // Клик по маркеру дрона
                        droneMarkers[drone.id].on('click', () => {
                            selectedDroneId = drone.id;
                            alert(`Дрон #${drone.id} выбран для назначения цели`);
                        });
                    } else {
                        droneMarkers[drone.id].setLatLng(latlng);
                        droneMarkers[drone.id].setRotationAngle(drone.heading || 0);
                        droneMarkers[drone.id].getPopup().setContent(popupContent);
                    }

                    // --- Линия трека ---
                    const trackLatLngs = drone.track.map(p => [parseFloat(p.latitude), parseFloat(p.longitude)]);
                    if (!trackPolylines[drone.id]) {
                        trackPolylines[drone.id] = L.polyline(trackLatLngs, {
                            color: 'blue'
                            , weight: 3
                        }).addTo(map);
                    } else {
                        trackPolylines[drone.id].setLatLngs(trackLatLngs);
                    }

                    // --- Маркер цели ---
                    if (drone.target?.latitude && drone.target?.longitude) {
                        const targetLatLng = [drone.target.latitude, drone.target.longitude];
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

        // === Клик по карте для назначения цели ===
        map.on('click', async function (e) {
            if (!selectedDroneId) {
                alert('Сначала выберите дрон (клик по маркеру)');
                return;
            }
            if (updatingTarget) return;

            const latlng = e.latlng;
            pendingTargetLatLng = latlng;
            updatingTarget = true;

            if (!confirm(`Назначить цель на [${latlng.lat.toFixed(5)}, ${latlng.lng.toFixed(5)}] для дрона #${selectedDroneId}?`)) {
                updatingTarget = false;
                return;
            }

            try {
                const response = await fetch(`/api/drones/${selectedDroneId}/target`, {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                    }
                    , body: JSON.stringify({
                        latitude: latlng.lat
                        , longitude: latlng.lng
                    })
                });
                if (!response.ok) throw new Error('Не удалось назначить цель');

                const targetData = await response.json();

                if (!targetMarkers[selectedDroneId]) {
                    targetMarkers[selectedDroneId] = L.marker([targetData.latitude, targetData.longitude], {
                        icon: createTargetIcon()
                    }).addTo(map);
                } else {
                    targetMarkers[selectedDroneId].setLatLng([targetData.latitude, targetData.longitude]);
                }

                alert(`Цель для дрона #${selectedDroneId} успешно назначена`);

            } catch (err) {
                alert(err.message);
            } finally {
                updatingTarget = false;
            }
        });

        // === Интервал обновления координат ===
        fetchCoordinates();
        setInterval(fetchCoordinates, 3000);
    </script>





@endsection