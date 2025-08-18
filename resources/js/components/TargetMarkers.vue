<template>
    <div style="display:none"></div>
</template>

<script setup>
import { watch } from "vue";
import L from "leaflet";

const props = defineProps({
    map: Object,
    drones: Array
});

const targetMarkers = {};

function createTargetIcon() {
    return L.icon({
        iconUrl: "/images/target-icon.svg",
        iconSize: [30, 30],
        iconAnchor: [15, 15],
    });
}

watch(() => props.drones, (drones) => {
    if (!props.map) return;

    drones.forEach((drone) => {
        if (drone.target && drone.target.latitude && drone.target.longitude) {
            const latlng = [drone.target.latitude, drone.target.longitude];
            if (!targetMarkers[drone.id]) {
                targetMarkers[drone.id] = L.marker(latlng, {
                    icon: createTargetIcon(),
                }).addTo(props.map);
            } else {
                targetMarkers[drone.id].setLatLng(latlng);
            }
        }
    });
}, { deep: true });
</script>
