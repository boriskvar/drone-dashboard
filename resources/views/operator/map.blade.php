@extends('layouts.main')

@section('title', 'Карта оператора')

@section('content')
<div class="container py-4">
    <h1>Карта дрона (гибридный подход)</h1>

    {{-- Группа кнопок --}}
    <div class="d-flex gap-2 mb-3">
        <button id="clear-tracks" class="btn btn-warning btn-sm">Очистить треки</button>
        <button id="set-target-btn" class="btn btn-danger btn-sm">Назначить цель</button>
    </div>

    <div id="map" style="height: 500px;"></div>
</div>
@endsection


@section('scripts')
<!-- === Подключение Leaflet  CSS и JS=== -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- <script src="https://unpkg.com/leaflet-rotatedmarker@0.2.0/leaflet.rotatedMarker.js"></script> -->

<script>
document.addEventListener('DOMContentLoaded', () => {

    // === [Переменные состояния] ===
    let activeDroneId = null;
    let selectedTargetLatLng = null;

    // === [Событие: Назначение цели по кнопке] ===
    // document.getElementById('set-target-btn').addEventListener('click', async () => {
    // ... код без изменений ...
    // });




    // === Создаём карту ===
    const map = L.map('map').setView([50.4501, 30.5234], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // === Хранилища (маркеров и треков) ===
    const markers = {};
    const tracks = {};
    const polylines = {};
    const targetMarkers = {};


    // === Функция создания иконки дрона  ===
    function createDroneIcon(heading = 0) {
        return L.icon({
            iconUrl: '/images/drone-arrow.svg', // путь к твоей иконке
            iconSize: [30, 30], // Физический размер на карте (в пикселях)
            iconAnchor: [15, 15], // Точка привязки (центр иконки)
            popupAnchor: [0, -15], // Смещение popup относительно иконки
            className: 'leaflet-drone-icon' // Для кастомных стилей (опционально)
        });
    }

    // ===  Клик по карте — назначаем цель ===
    map.on('click', function(e) {
        if (!activeDroneId) {
            alert('Сначала выберите дрон (клик по маркеру)');
            return;
        }

        /* const latlng = e.latlng;
        selectedTargetLatLng = latlng; */
        selectedTargetLatLng = e.latlng;

        // Удаляем старый маркер цели, если есть
        if (targetMarkers[activeDroneId]) {
            map.removeLayer(targetMarkers[activeDroneId]);
        }

        // === Добавляем новый маркер цели ===
        targetMarkers[activeDroneId] = L.marker(selectedTargetLatLng, {
            icon: L.icon({
                iconUrl: '/images/target-icon.svg', // или временная иконка
                iconSize: [30, 30],
                iconAnchor: [15, 15],
                popupAnchor: [0, -15]
            })
        }).addTo(map).bindPopup("Новая цель для дрона #" + activeDroneId).openPopup();

        // Отправляем цель на сервер
        fetch('/api/target', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                    'content')
            },
            body: JSON.stringify({
                drone_id: activeDroneId,
                lat: selectedTargetLatLng.lat,
                lng: selectedTargetLatLng.lng
            })
        }).then(res => {
            if (!res.ok) {
                console.error("Ошибка при установке цели");
            }
        });
    });

    // === [Анимация перемещения маркера] ===
    /* function animateMarker(marker, newLatLng) {
        const duration = 1000;
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
                marker.setLatLng(newLatLng);
            }
        };

        move();
    }
     */


    // === Функция обновления координат с сервера ===
    async function fetchCoordinates() {
        try {
            const response = await fetch('/api/coordinates');
            if (!response.ok) throw new Error('Network error');

            const drones = await response.json();

            drones.forEach(drone => {
                if (!drone.lat || !drone.lng || !Array.isArray(drone.track)) return;

                const latlng = [drone.lat, drone.lng];

                // === Маркер дрона: создание и обновление ===
                const popupContent = `
                    <strong>Дрон #${drone.id}:</strong> ${drone.name || ''}<br>
                    Широта: ${drone.lat}<br>
                    Долгота: ${drone.lng}<br>
                    Высота: ${drone.altitude ?? '—'} м<br>
                    Скорость: ${drone.speed ?? '—'} км/ч<br>
                    Курс: ${drone.heading ?? '—'}°<br>
                    Обновлено: ${drone.updated_at}
                `;

                if (!markers[drone.id]) {
                    const marker = L.marker(latlng, {
                        icon: createDroneIcon(drone.heading || 0)
                    }).addTo(map).bindPopup(popupContent);

                    marker.on('click', () => {
                        activeDroneId = drone.id;
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
                /* else {
                    markers[drone.id].setLatLng(latlng);
                    markers[drone.id].getPopup().setContent(
                        `Дрон #${drone.id}: ${drone.name || ''}`);
                } */

                // === Обновление трека ===
                // tracks[drone.id] = drone.track.map(p => [p.lat, p.lng]);
                tracks[drone.id] = drone.track.map(p => [parseFloat(p.lat), parseFloat(p.lng)]);


                // === Polyline для трека ===
                if (!polylines[drone.id]) {
                    polylines[drone.id] = L.polyline(tracks[drone.id], {
                        color: 'blue',
                        weight: 3
                    }).addTo(map);
                } else {
                    polylines[drone.id].setLatLngs(tracks[drone.id]);
                }

                // === 🎯 Отображение цели из БД ===
                if (drone.target && drone.target.lat && drone.target.lng) {
                    // const targetLatLng = [drone.target.lat, drone.target.lng];
                    const targetLatLng = [parseFloat(drone.target.lat), parseFloat(drone.target
                        .lng)];

                    if (!targetMarkers[drone.id]) {
                        targetMarkers[drone.id] = L.marker(targetLatLng, {
                            icon: L.icon({
                                iconUrl: '/images/target-icon.svg',
                                iconSize: [30, 30],
                                iconAnchor: [15, 15],
                                popupAnchor: [0, -15]
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

            }); // конец drones.forEach

        } catch (error) {
            console.error('Ошибка при получении координат:', error);
        }
    }

    // === Очистка всех треков с кнопки ===
    /*
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
     */
    // === Кнопка очистки треков ===
    const clearBtn = document.getElementById('clear-tracks');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            Object.values(polylines).forEach(polyline => {
                if (polyline) map.removeLayer(polyline);
            });
            Object.keys(tracks).forEach(key => tracks[key] = []);
        });
    }

    // === ⏱️ Первичная загрузка и автообновление каждые 3 секунды ===
    fetchCoordinates();
    setInterval(fetchCoordinates, 3000);

}); // конец DOMContentLoaded
</script>

@endsection
