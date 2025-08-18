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

const trackPolylines = {};

watch(() => props.drones, (drones) => {
    if (!props.map) return;

    drones.forEach((drone) => {
        const trackLatLngs = drone.track.map((p) => [
            parseFloat(p.latitude),
            parseFloat(p.longitude),
        ]);

        if (!trackPolylines[drone.id]) {
            trackPolylines[drone.id] = L.polyline(trackLatLngs, {
                color: "blue",
                weight: 3,
            }).addTo(props.map);
        } else {
            trackPolylines[drone.id].setLatLngs(trackLatLngs);
        }
    });
}, { deep: true });
</script>
