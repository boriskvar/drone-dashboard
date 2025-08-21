<template>
    <div>
        <div id="simulation-map" style="height: 600px;"></div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import L from "leaflet";

// Пропсы от Blade
const props = defineProps({
    drones: {
        type: Array,
        required: true
    }
})

const drones = ref([]);
let markers = {}; // чтобы обновлять позиции дронов
const paths = {}; // для линий трека

let map;

// Иконка дрона
function createDroneIcon() {
    return L.icon({
        iconUrl: "/images/drone-arrow.svg",
        iconSize: [40, 40],
        iconAnchor: [20, 20],
    });
}

// Получение координат всех дронов и их треков
async function fetchDrones() {
    try {
        const res = await fetch("/api/drones/flight-data");
        const data = await res.json();
        drones.value = data;

        data.forEach(drone => {
            const latlng = [drone.latitude, drone.longitude];

            // Маркер текущей позиции
            if (!markers[drone.id]) {
                markers[drone.id] = L.marker(latlng, { icon: createDroneIcon() })
                    .addTo(map)
                    .bindPopup(`Дрон #${drone.id}`);
            } else {
                markers[drone.id].setLatLng(latlng);
            }

            // Трек (polyline)
            if (!paths[drone.id]) {
                paths[drone.id] = L.polyline([[drone.latitude, drone.longitude]], { color: 'blue' }).addTo(map);
            } else {
                // добавляем новую точку в конец трека
                paths[drone.id].addLatLng([drone.latitude, drone.longitude]);
            }
        });

    } catch (err) {
        console.error("Ошибка получения координат дронов:", err);
    }
}

// Запуск/остановка симуляции
async function toggleSimulation(droneId, start = true) {
    const url = start
        ? `/api/drones/simulation/start/${droneId}`
        : `/api/drones/simulation/stop/${droneId}`;

    try {
        const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' } });
        const data = await res.json();
        alert(data.message);
    } catch (err) {
        console.error("Ошибка симуляции:", err);
    }
}

onMounted(() => {
    map = L.map("simulation-map").setView([50.4501, 30.5234], 6);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map);

    fetchDrones();
    setInterval(fetchDrones, 3000); // обновляем каждые 3 сек
});
</script>
