<template>
    <MapContainer @map-ready="onMapReady" />
    <DroneMarkers v-if="map" :map="map" :drones="drones" @drone-selected="onDroneSelected" />
    <TrackPolylines v-if="map" :map="map" :drones="drones" />
    <TargetMarkers v-if="map" :map="map" :drones="drones" />
</template>

<script setup>
import { ref, onMounted } from "vue";
import MapContainer from "./MapContainer.vue";
import DroneMarkers from "./DroneMarkers.vue";
import TrackPolylines from "./TrackPolylines.vue";
import TargetMarkers from "./TargetMarkers.vue";

const props = defineProps({
    initialDrones: Array,
});

const map = ref(null);
const drones = ref(props.initialDrones || []);
const selectedDroneId = ref(null);

function onMapReady(m) {
    map.value = m;
}

function onDroneSelected(id) {
    selectedDroneId.value = id;
    alert(`Дрон #${id} выбран`);
}

async function fetchCoordinates() {
    const response = await fetch("/api/drones/flight-data");
    drones.value = await response.json();
}

onMounted(() => {
    fetchCoordinates();
    setInterval(fetchCoordinates, 3000);
});
</script>
