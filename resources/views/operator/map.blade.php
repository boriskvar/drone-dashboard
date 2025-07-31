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

<!-- плагин: leaflet-rotatedmarker -->
<script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ✅ 1. Сначала объявляем функцию генерации SVG-иконки
        function createDroneIcon(heading = 0) {
            const svg = `
        <svg width="40" height="40" viewBox="0 0 24 24"
             xmlns="http://www.w3.org/2000/svg"
             style="transform: rotate(${heading}deg); transform-origin: center;">
          <polygon points="12,2 22,22 12,17 2,22" fill="#007bff"/>
        </svg>`;

            return L.divIcon({
                className: '',
                html: svg,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
        }

        // ✅ 2. Тут создаётся карта
        const map = L.map('map').setView([50.4501, 30.5234], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = {};
        const tracks = {};
        const polylines = {};
        let targetMarkers = {}; // ✅ сюда вставляем обработчик:

        map.on('click', function(e) {
            if (!window.activeDroneId) {
                alert("Выбери дрона для назначения цели");
                return;
            }

            const latlng = e.latlng;

            // Удалим старый маркер цели (если был)
            if (targetMarkers[activeDroneId]) {
                map.removeLayer(targetMarkers[activeDroneId]);
            }

            // Новый маркер
            targetMarkers[activeDroneId] = L.marker(latlng, {
                icon: L.icon({
                    iconUrl: '/images/target-icon.svg', // добавь такую иконку или временно используй любую
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                })
            }).addTo(map).bindPopup("Цель для дрона #" + activeDroneId).openPopup();

            // Отправим в API
            fetch('/api/target', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    drone_id: activeDroneId,
                    lat: latlng.lat,
                    lng: latlng.lng
                })
            }).then(res => {
                if (!res.ok) {
                    console.error("Ошибка при установке цели");
                }
            });
        });



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

                    // Маркер
                    const popupContent = `
                <strong>Дрон #${drone.id}:</strong> ${drone.name || ''}<br>
                Широта: ${drone.lat}<br>
                Долгота: ${drone.lng}<br>
                Высота: ${drone.altitude ?? '—'} м<br>
                Скорость: ${drone.speed ?? '—'} км/ч<br>
                Курс: ${drone.heading ?? '—'}°<br>
                Обновлено: ${drone.updated_at}
            `;

                    // Проверка на существование маркера
                    if (!markers[drone.id]) {
                        const marker = L.marker(latlng, {
                            icon: createDroneIcon(drone.heading || 0)
                        }).addTo(map).bindPopup(popupContent);

                        marker.on('click', () => {
                            window.activeDroneId = drone.id;
                            console.log('Выбран дрон #' + drone.id);
                        });

                        markers[drone.id] = marker;
                    } else {
                        markers[drone.id].getPopup().setContent(popupContent);
                        animateMarker(markers[drone.id], L.latLng(latlng));

                        if (typeof drone.heading === 'number') {
                            markers[drone.id].setIcon(createDroneIcon(drone.heading));
                        }
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

                    // 🟡 ДОБАВЛЕНИЕ ЦЕЛИ (target)
                    // 🎯 Отображение цели из БД
                    if (drone.target && drone.target.lat && drone.target.lng) {
                        const targetLatLng = [drone.target.lat, drone.target.lng];

                        if (!targetMarkers[drone.id]) {
                            targetMarkers[drone.id] = L.marker(targetLatLng, {
                                icon: L.icon({
                                    iconUrl: '/images/target-icon.svg',
                                    iconSize: [30, 30],
                                    iconAnchor: [15, 15]
                                })
                            }).addTo(map).bindPopup("Цель для дрона #" + drone.id);
                        } else {
                            targetMarkers[drone.id].setLatLng(targetLatLng);
                        }
                    } else if (targetMarkers[drone.id]) {
                        // Удаляем цель, если она исчезла в БД
                        map.removeLayer(targetMarkers[drone.id]);
                        delete targetMarkers[drone.id];
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
