<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\FlightData;
use App\Models\Target;
use App\Models\Drone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiFlightDataController extends Controller
{
    /**
     * Получить данные одного дрона
     */

    public function show(int $droneId): JsonResponse
    {
        $drone = Drone::findOrFail($droneId);

        $last = FlightData::where('drone_id', $drone->id)
            ->orderByDesc('created_at')
            ->first(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

        if (!$last) {
            return response()->json(['error' => 'No flight data found'], 404);
        }

        $track = FlightData::where('drone_id', $drone->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get(['latitude', 'longitude'])
            ->reverse()
            ->values();

        $target = Target::where('drone_id', $drone->id)
            ->latest('created_at')
            ->first(['latitude', 'longitude']);

        return response()->json($this->formatDroneData($drone, $last, $track, $target));
    }


    /**
     * Получить координаты всех активных дронов
     */
    public function latestPositions(): JsonResponse
    {
        $drones = Drone::all();

        $result = $drones->map(function ($drone) {
            $last = FlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->first(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

            if (!$last) {
                return null; // если нет данных — пропускаем
            }

            $track = FlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['latitude', 'longitude'])
                ->reverse()
                ->values();

            $target = Target::where('drone_id', $drone->id)
                ->latest('created_at')
                ->first(['latitude', 'longitude']);

            return $this->formatDroneData($drone, $last, $track, $target);
        })->filter()->values(); // убираем null, если нет полётов

        return response()->json($result);
    }


    /**
     * Форматирование структуры данных дрона
     */
    private function formatDroneData(Drone $drone, $last, $track, $target): array
    {
        return [
            'id'         => $drone->id,
            'name'       => $drone->name,
            'latitude'   => $last->latitude,
            'longitude'  => $last->longitude,
            'altitude'   => $last->altitude,
            'speed'      => $last->speed,
            'heading'    => $last->heading,
            'updated_at' => $last->created_at->toDateTimeString(),
            'track'      => $track,
            'target'     => $target,
        ];
    }



    /**
     * ▶️ Запуск симуляции движения для дрона
     */
    /* public function startSimulation(Drone $drone, Request $request)
    {
        // Тут позже будет запуск генератора координат (Job / Command / Service)
        // Пока делаем заглушку
        return response()->json([
            'status' => 'ok',
            'message' => "Симуляция запущена для дрона #{$drone->id}",
        ]);
    } */

    /**
     * ⏹ Остановка симуляции движения для дрона
     */
    /* public function stopSimulation(Drone $drone)
    {
        // Тут позже будет остановка генератора
        return response()->json([
            'status' => 'ok',
            'message' => "Симуляция остановлена для дрона #{$drone->id}",
        ]);
    } */



    public function startSimulation($droneId)
    {
        // Генерируем новые координаты каждые 2 сек через Laravel job/queue или таймер (упрощённо через cache)
        Cache::put("sim_drone_$droneId", true);

        return response()->json(['message' => "Симуляция дрона $droneId запущена"]);
    }

    public function stopSimulation($droneId)
    {
        Cache::forget("sim_drone_$droneId");

        return response()->json(['message' => "Симуляция дрона $droneId остановлена"]);
    }

    // Метод для генерации новой позиции (можно вызывать через Laravel Command/Job каждые 2 сек)
    public function simulateDroneMovement($droneId)
    {
        if (!Cache::get("sim_drone_$droneId")) return;

        $drone = Drone::find($droneId);
        if (!$drone) return;

        // Простейшая имитация движения (смещаем на маленькую случайную величину)
        $newLat = $drone->latitude + (rand(-5, 5) / 10000);
        $newLng = $drone->longitude + (rand(-5, 5) / 10000);

        $drone->latitude = $newLat;
        $drone->longitude = $newLng;
        $drone->save();

        // Сохраняем в flight_data
        $drone->flightData()->create([
            'latitude' => $newLat,
            'longitude' => $newLng,
            'altitude' => 100, // можно рандом
            'speed' => 200,
            'heading' => rand(0, 360),
        ]);
    }

    /**
     * 📍 Получить трек движения дрона
     */
    public function track(Drone $drone)
    {
        // Загружаем все записи по данному дрону, по времени
        $data = $drone->flightData()
            ->orderBy('created_at')
            ->get(['latitude', 'longitude', 'created_at']);

        return response()->json($data);
    }
}