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
    // === Инициализация карты ===
        let map = L.map('map').setView([50.4501, 30.5234], 13); // Создаём карту в #map и ставим начальный вид на Киев.
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { // Подключаем слой OpenStreetMap
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        //Глобальные переменные
        let selectedDroneId = null;  // id выбранного дрона (какой дрон сейчас выбран (кликнут маркером))
        let updatingTarget = false; // флаг "цель сейчас обновляется" (блокирует повторные клики, пока сервер не ответил)
        // let pendingTargetLatLng = null; // координаты цели после клика по карте

        //Хранилища слоёв:
        const droneMarkers = {}; // словарь, чтобы быстро находить маркеры на карте по ID дрона
        const targetMarkers = {}; // словарь, чтобы быстро находить маркеры на карте по ID цели
        const trackPolylines = {};

        // === Иконки ===
        function createDroneIcon() {
            return L.icon({
                iconUrl: '/images/drone-arrow.svg',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });
        }

        function createTargetIcon() {
            return L.icon({
                iconUrl: '/images/target-icon.svg',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
        }

        // === Загрузка координат дронов ===
        async function fetchCoordinates() {
            try {
                const response = await fetch('/api/drones/flight-data'); //получаем массив дронов
                if (!response.ok) throw new Error('Не удалось получить данные дронов');

                const drones = await response.json();

                //Для каждого дрона:
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

                    // если нет — создаём маркер дрона L.marker(...), задаём поворот, биндим попап с данными.
                    // создаём или двигаем маркер дрона
                    if (!droneMarkers[drone.id]) {
                        droneMarkers[drone.id] = L.marker(latlng, {
                            icon: createDroneIcon(),
                            rotationAngle: drone.heading || 0,
                            rotationOrigin: 'center center'
                        }).addTo(map).bindPopup(popupContent);

                        // навешиваем обработчик marker.on('click', ...) → выставляем selectedDroneId = drone.id.
                        // обработчик клика по маркеру
                        droneMarkers[drone.id].on('click', () => {
                            selectedDroneId = drone.id;
                            alert(`Дрон #${drone.id} выбран для назначения цели`);
                        });

                    } else {
                        // если есть — обновляем coords, rotation и содержимое попапа
                        // обновляем позицию и угол
                        droneMarkers[drone.id].setLatLng(latlng);
                        droneMarkers[drone.id].setRotationAngle(drone.heading || 0);
                        droneMarkers[drone.id].getPopup().setContent(popupContent);
                    }

                    // --- трек ---
                    const trackLatLngs = drone.track.map(p => [parseFloat(p.latitude), parseFloat(p.longitude)]);
                    // если нет — создаём `L.polyline([...])`
                    if (!trackPolylines[drone.id]) {
                        trackPolylines[drone.id] = L.polyline(trackLatLngs, {
                            color: 'blue',
                            weight: 3
                        }).addTo(map);
                    } else {
                        // если есть — `setLatLngs(...)` новыми точками.
                        trackPolylines[drone.id].setLatLngs(trackLatLngs);
                    }

                    // --- цель ---
                    // если в ответе есть `drone.target` с latitude и longitude:
            if (drone.target && drone.target.latitude && drone.target.longitude) {
                if (!targetMarkers[drone.id]) {
                    targetMarkers[drone.id] = L.marker(
                        [drone.target.latitude, drone.target.longitude],
                        { icon: createTargetIcon() }
                    ).addTo(map);
                } else {
                    targetMarkers[drone.id].setLatLng([drone.target.latitude, drone.target.longitude]);
                }
            }
        });

            } catch (error) {
                console.error('Ошибка при получении координат:', error);
            }
        }

        // === Клик по карте (назначение новой цели) ===
        map.on('click', async function (e) {
            // Если дрон не выбран → alert('Сначала выберите дрон...') и выход.
            if (!selectedDroneId) {
                alert('Сначала выберите дрон (клик по маркеру)');
                return;
            }

            // Если уже идёт обновление (updatingTarget === true) → игнор.
            if (updatingTarget) return; // блокируем повторный клик

            // Берём e.latlng, просим подтверждение через confirm(...).
            const latlng = e.latlng; // координаты клика
            updatingTarget = true; // блокируем новые клики до ответа сервера


            // Если «`Отмена`» → `снимаем` updatingTarget и `выходим`.
            if (!confirm(
                `Назначить цель на [${latlng.lat.toFixed(5)}, ${latlng.lng.toFixed(5)}] для дрона #${selectedDroneId}?`
            )) {
                updatingTarget = false;
                return;
            }

            // Если «ОК» → POST /api/drones/{selectedDroneId}/target с {latitude, longitude}.
            try {
                const response = await fetch(`/api/drones/${selectedDroneId}/target`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        latitude: latlng.lat,
                        longitude: latlng.lng
                    })
                });
                if (!response.ok) throw new Error('Не удалось назначить цель');

                const targetData = await response.json();

                if (!targetMarkers[selectedDroneId]) {
                    // создаём локальный маркер цели (с координатами) для этого дрона;
                    targetMarkers[selectedDroneId] = L.marker([targetData.latitude, targetData.longitude], {
                        icon: createTargetIcon()
                    }).addTo(map);
                } else {
                    // обновляем локальный маркер цели (с координатами) для этого дрона;
                    targetMarkers[selectedDroneId].setLatLng([targetData.latitude, targetData.longitude]);
                }

                // показываем alert('Цель назначена...').
                alert(`Цель для дрона #${selectedDroneId} успешно назначена`);

            } catch (err) {
                alert(err.message);
            } finally {
                // В finally всегда ставим updatingTarget = false, чтобы разблокировать дальнейшие клики.
                updatingTarget = false;
            }
        });

        // === Интервал обновления координат ===
        fetchCoordinates();
        setInterval(fetchCoordinates, 3000);
</script>





@endsection
