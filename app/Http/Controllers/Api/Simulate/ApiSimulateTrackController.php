<?php

namespace App\Http\Controllers\Api\Simulate;

use App\Models\Drone;
use App\Models\Simulate\SimulateTrack;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiSimulateTrackController extends Controller
{
    // Получить список активных дронов с последней точкой и треком (последние 20 точек)
    public function index(): JsonResponse
    {
        $activeDrones = Drone::where('status', 'active')->get();

        $result = [];

        foreach ($activeDrones as $drone) {
            $track = SimulateTrack::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['latitude', 'longitude'])
                ->reverse()
                ->values();

            // Посмотрим трек дрона прямо здесь
            dd($drone->id, $track);

            $lastPoint = $track->last(); // Последняя точка — текущее положение

            if ($lastPoint) {
                $result[] = [
                    'id' => $drone->id,
                    'name' => $drone->name,
                    'latitude' => $lastPoint->latitude,
                    'longitude' => $lastPoint->longitude,
                    'track' => $track,
                ];
            }
        }

        return response()->json($result);
    }

    // Получить полный трек по drone_id
    public function track(int $droneId): JsonResponse
    {
        $positions = SimulateTrack::where('drone_id', $droneId)
            ->orderBy('created_at')
            ->get(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

        return response()->json($positions);
    }
}
