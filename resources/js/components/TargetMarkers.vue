<template>
    <!-- Компонент работает только с картой -->
    <div style="display:none"></div>
</template>

<script setup>
import { watch, onMounted, onBeforeUnmount } from "vue";
import L from "leaflet";

const props = defineProps({
    map: Object,
    drones: Array,
    selectedDroneId: Number,
});

const targetMarkers = {};

// === Иконка цели ===
function createTargetIcon() {
    return L.icon({
        iconUrl: "/images/target-icon.svg",
        iconSize: [30, 30],
        iconAnchor: [15, 15],
    });
}

// === Отрисовка целей из API ===
watch(
    () => props.drones,
    (drones) => {
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
    },
    { deep: true }
);

// === Установка новой цели по клику ===
async function handleMapClick(e) {
    if (!props.selectedDroneId) {
        alert("Сначала выберите дрон (клик по маркеру)");
        return;
    }

    const { lat, lng } = e.latlng;
    console.log(`TargetMarkers: новая цель для дрона ${props.selectedDroneId}`, lat, lng);

    try {
        const response = await fetch(`/api/drones/${props.selectedDroneId}/target`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ latitude: lat, longitude: lng }),
        });

        if (!response.ok) throw new Error("Ошибка при обновлении цели");

        const data = await response.json();
        console.log("TargetMarkers: ответ от сервера", data);

        // Отрисуем маркер локально
        if (!targetMarkers[props.selectedDroneId]) {
            targetMarkers[props.selectedDroneId] = L.marker([lat, lng], {
                icon: createTargetIcon(),
            }).addTo(props.map);
        } else {
            targetMarkers[props.selectedDroneId].setLatLng([lat, lng]);
        }

        // alert("Цель успешно сохранена ✅");
        // Показываем alert с координатами
        alert(`Цель успешно сохранена ✅\nДрон #${props.selectedDroneId}\nКоординаты: ${lat.toFixed(6)}, ${lng.toFixed(6)}`);
    } catch (err) {
        console.error("TargetMarkers: ошибка при сохранении цели", err);
        alert("Не удалось установить цель ❌");
    }
}

// === Подписка на клики карты ===
onMounted(() => {
    if (props.map) {
        props.map.on("click", handleMapClick);
    }
});

onBeforeUnmount(() => {
    if (props.map) {
        props.map.off("click", handleMapClick);
    }
});
</script>
