<?php

namespace App\Http\Controllers\Api\Simulate;

use App\Models\Drone;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\SimulateDronePosition;

class SimulateDroneController extends Controller
{
    /**
     * Вернуть список активных дронов с последними координатами и треком.
     */
    public function index(): JsonResponse
    {
        $activeDrones = Drone::where('status', 'active')->get();

        $result = [];

        foreach ($activeDrones as $drone) {
            $track = SimulateDronePosition::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['lat', 'lng'])
                ->reverse()
                ->values(); // чтобы индекс был 0,1,2...

            $last = $track->last(); // Последняя точка — текущее положение

            if ($last) {
                $result[] = [
                    'id' => $drone->id,
                    'name' => $drone->name,
                    'lat' => $last->lat,
                    'lng' => $last->lng,
                    'track' => $track,
                ];
            }
        }

        return response()->json($result);
    }

    /**
     * Сохранить имитированную позицию дрона.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        // Обновить координаты дрона (опционально)
        Drone::where('id', $data['drone_id'])->update([
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ]);

        // Сохранить новую позицию в simulate_drone_positions
        SimulateDronePosition::create([
            'drone_id' => $data['drone_id'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'recorded_at' => now(),
        ]);

        return response()->json(['message' => 'Simulated position stored']);
    }

    /**
     * Вернуть последние координаты одного дрона.
     */
    public function getLatest(Request $request): JsonResponse
    {
        $id = $request->input('id');
        $drone = Drone::select('id', 'lat', 'lng')->find($id);

        if (!$drone) {
            return response()->json(['message' => 'Drone not found'], 404);
        }

        return response()->json($drone);
    }
}