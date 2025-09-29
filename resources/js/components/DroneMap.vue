<template>
    <MapContainer @map-ready="onMapReady" />

    <!-- Маркеры дронов -->
    <DroneMarkers
                  v-if="map"
                  :map="map"
                  :drones="drones"
                  @drone-selected="onDroneSelected" />

    <!-- Линии треков -->
    <TrackPolylines v-if="map" :map="map" :drones="drones" />

    <!-- Маркеры целей, передаём выбранного дрона -->
    <TargetMarkers
                   v-if="map"
                   :map="map"
                   :drones="drones"
                   :selected-drone-id="selectedDroneId" />
</template>

<script setup>
import { ref, onMounted } from "vue";
import MapContainer from "./MapContainer.vue";
import DroneMarkers from "./DroneMarkers.vue";
import TrackPolylines from "./TrackPolylines.vue";
import TargetMarkers from "./TargetMarkers.vue";

const props = defineProps({
    initialDrones: {
        type: Array,
        default: () => [],
    },
});

const map = ref(null);
const drones = ref(props.initialDrones);
const selectedDroneId = ref(null);

// Получаем карту из MapContainer
function onMapReady(m) {
    if (!m) {
        console.warn("DroneMap: карта не инициализирована");
        return;
    }
    map.value = m;
}

// Обработка клика по дрону
function onDroneSelected(id) {
    console.log("DroneMap: выбран дрон", id, typeof id);
    selectedDroneId.value = Array.isArray(id) ? id[0] : id;
    alert(`Дрон #${selectedDroneId.value} выбран`);
}

// Подгрузка актуальных координат дронов
async function fetchCoordinates() {
    try {
        const response = await fetch("/api/drones/flight-data");
        if (!response.ok) throw new Error("Ошибка при получении данных дронов");
        const data = await response.json();
        if (Array.isArray(data)) {
            drones.value = data;
        } else {
            console.warn("DroneMap: API вернул не массив", data);
        }
    } catch (err) {
        console.error("DroneMap: fetchCoordinates error", err);
    }
}

onMounted(() => {
    fetchCoordinates();
    setInterval(fetchCoordinates, 3000);
});
</script>
