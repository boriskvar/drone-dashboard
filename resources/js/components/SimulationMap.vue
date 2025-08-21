<template>
    <div id="simulation-map" style="height: 600px;"></div>
</template>

<script setup>
import { onMounted } from 'vue'
import L from 'leaflet'

let map
let markers = {}
let polylines = {}
let firstFit = true // флаг, чтобы fitBounds сделать только один раз

// --- Иконка дрона (стрелка) ---
function createDroneIcon(rotation = 0) {
    return L.divIcon({
        html: `<img src="/images/drone-arrow.svg" style="width:40px; transform: rotate(${rotation}deg)">`,
        iconSize: [40, 40],
        className: ""
    })
}

// --- Загрузка дронов ---
const fetchDrones = async () => {
    try {
        const response = await fetch('/api/drones/flight-data')
        const drones = await response.json()

        let bounds = [] // сюда будем собирать координаты

        drones.forEach(drone => {
            const lat = drone.latitude
            const lng = drone.longitude

            if (!lat || !lng) return

            bounds.push([lat, lng]) // добавляем для автоцентра

            // --- маркер ---
            if (markers[drone.id]) {
                markers[drone.id].setLatLng([lat, lng])
                markers[drone.id].setIcon(createDroneIcon(drone.heading ?? 0))
            } else {
                markers[drone.id] = L.marker([lat, lng], {
                    icon: createDroneIcon(drone.heading ?? 0)
                })
                    .addTo(map)
                    .bindPopup(`
                    <b>${drone.name}</b><br>
                    Lat: ${lat}, Lng: ${lng}<br>
                    Speed: ${drone.speed ?? '-'}<br>
                    Alt: ${drone.altitude ?? '-'}
                `)
            }

            // --- трек ---
            if (drone.track && drone.track.length > 1) {
                const trackLatLngs = drone.track.map(p => [p.latitude, p.longitude])

                if (polylines[drone.id]) {
                    polylines[drone.id].setLatLngs(trackLatLngs)
                } else {
                    polylines[drone.id] = L.polyline(trackLatLngs, {
                        color: 'blue',
                        weight: 2
                    }).addTo(map)
                }
            }
        })

        // --- Автоцентрирование (только первый раз) ---
        if (firstFit && bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] })
            firstFit = false
        }
    } catch (error) {
        console.error('Ошибка загрузки дронов:', error)
    }
}

onMounted(() => {
    map = L.map("simulation-map").setView([50.4501, 30.5234], 6)

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map)

    fetchDrones()
    setInterval(fetchDrones, 3000)
})
</script>

<style>
#simulation-map {
    width: 100%;
}
</style>
