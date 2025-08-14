<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\FlightData;
use App\Models\Target;
use App\Models\Drone;

class ApiFlightDataController extends Controller
{
    /**
     * Получить данные одного дрона
     */
    public function show(int $droneId): JsonResponse
    {
        $drone = Drone::findOrFail($droneId);

        $track = FlightData::where('drone_id', $drone->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get(['latitude', 'longitude'])
            ->reverse()
            ->values();

        $last = FlightData::where('drone_id', $drone->id)
            ->orderByDesc('created_at')
            ->first(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

        $target = Target::where('drone_id', $drone->id)
            ->first(['latitude', 'longitude']);

        if (!$last) {
            return response()->json(['error' => 'No flight data found'], 404);
        }

        return response()->json($this->formatDroneData($drone, $last, $track, $target));
    }

    /**
     * Получить координаты всех активных дронов
     */
    public function latestPositions(): JsonResponse
    {
        $drones = Drone::all();
        $result = [];

        foreach ($drones as $drone) {
            $track = FlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['latitude', 'longitude'])
                ->reverse()
                ->values();

            $last = FlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->first(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

            $target = Target::where('drone_id', $drone->id)
                ->first(['latitude', 'longitude']);

            if ($last) {
                $result[] = $this->formatDroneData($drone, $last, $track, $target);
            }
        }

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
}
