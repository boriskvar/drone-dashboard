<template>
    <div id="map" style="height: 600px;"></div>
</template>

<script setup>
import { onMounted } from "vue";
import L from "leaflet";

const emit = defineEmits(["map-ready"]);

onMounted(() => {
    const map = L.map("map").setView([50.4501, 30.5234], 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    emit("map-ready", map);
});
</script>
