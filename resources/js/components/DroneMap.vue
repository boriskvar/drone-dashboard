<template>
    <div id="map" style="height: 600px;"></div>
</template>

<script setup>
import { onMounted } from 'vue'
import L from 'leaflet'

// Фикс путей к иконкам (иначе они не отображаются в Laravel + Vite)
delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({
    iconRetinaUrl: new URL('leaflet/dist/images/marker-icon-2x.png', import.meta.url).href,
    iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).href,
    shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).href,
})

const props = defineProps({
    initialDrones: {
        type: Array,
        required: true
    }
})

onMounted(() => {
    const map = L.map('map').setView([50.4501, 30.5234], 11) // Центр Киева

    // 🌑 Тёмная карта (Carto DarkMatter)
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://carto.com/">CARTO</a>, © OpenStreetMap contributors',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map)

    props.initialDrones.forEach(drone => {
        L.marker([drone.lat, drone.lng])
            .addTo(map)
            .bindPopup(`Дрон: ${drone.name || 'Без названия'}`)
    })
})
</script>

<style scoped>
#map {
    width: 100%;
    border: 1px solid #333;
    border-radius: 8px;
}
</style>
