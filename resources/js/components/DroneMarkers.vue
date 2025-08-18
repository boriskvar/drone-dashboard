<template>
    <!-- Этот компонент не рендерит HTML, он только работает с Leaflet -->
    <div style="display:none"></div>
</template>

<script setup>
import { watch } from "vue";
import L from "leaflet";
import "leaflet-rotatedmarker";

const props = defineProps({
    map: Object,
    drones: Array
});

const emit = defineEmits(["drone-selected"]);

const droneMarkers = {};

function createDroneIcon() {
    return L.icon({
        iconUrl: "/images/drone-arrow.svg",
        iconSize: [40, 40],
        iconAnchor: [20, 20],
    });
}

watch(() => props.drones, (drones) => {
    if (!props.map) return;

    drones.forEach((drone) => {
        const latlng = [drone.latitude, drone.longitude];
        const popupContent = `
      <strong>Дрон #${drone.id}:</strong> ${drone.name || ""}<br>
      Широта: ${drone.latitude}<br>
      Долгота: ${drone.longitude}<br>
      Высота: ${drone.altitude ?? "—"} м<br>
      Скорость: ${drone.speed ?? "—"} км/ч<br>
      Курс: ${drone.heading ?? "—"}°<br>
      Обновлено: ${drone.updated_at}
    `;

        if (!droneMarkers[drone.id]) {
            droneMarkers[drone.id] = L.marker(latlng, {
                icon: createDroneIcon(),
                rotationAngle: drone.heading || 0,
                rotationOrigin: "center center",
            })
                .addTo(props.map)
                .bindPopup(popupContent);

            droneMarkers[drone.id].on("click", () => {
                emit("drone-selected", drone.id);
            });
        } else {
            droneMarkers[drone.id].setLatLng(latlng);
            droneMarkers[drone.id].setRotationAngle(drone.heading || 0);
            droneMarkers[drone.id].getPopup().setContent(popupContent);
        }
    });
}, { deep: true });
</script>
