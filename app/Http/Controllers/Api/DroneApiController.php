<?php

namespace App\Http\Controllers\Api;

use App\Models\Drone;
use Illuminate\Http\Request;

use App\Models\DronePosition;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class DroneApiController extends Controller
{
    // Пример: получить текущий статус дронов
    /*     public function index()
    {
        return response()->json([
            [
                'id' => 1,
                'status' => 'active',
                'battery' => 87,
                'lat' => 50.4501,
                'lng' => 30.5234,
                'altitude' => 120,
            ],
            [
                'id' => 2,
                'status' => 'offline',
                'battery' => null,
            ]
        ]);
    } */

    /**
     * Вернуть список всех дронов с координатами.
     */
    /*     public function index(): JsonResponse
    {
        $drones = Drone::select('id', 'lat', 'lng')->get();
        return response()->json($drones);
    } */

    public function index(): JsonResponse
    {
        $activeDrones = Drone::where('status', 'active')->get();

        $result = [];

        foreach ($activeDrones as $drone) {
            $track = \App\Models\DronePosition::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['lat', 'lng'])
                ->reverse()
                ->values(); // чтобы индекс был 0,1,2...

            $last = $track->last(); // Последняя точка — как текущее положение

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
     * Сохранить телеметрию от дрона.
     */
    /* public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id'    => 'required|exists:drones,id',
            'lat'   => 'required|numeric',
            'lng'   => 'required|numeric',
        ]);

        $drone = Drone::findOrFail($validated['id']);
        $drone->lat = $validated['lat'];
        $drone->lng = $validated['lng'];
        $drone->save();

        return response()->json(['message' => 'Telemetry saved'], 201);
    } */

    public function store(Request $request)
    {
        $data = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        // Обновляем координаты дрона в основной таблице (если нужно)
        Drone::where('id', $data['drone_id'])->update([
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ]);

        // Сохраняем новую точку трека
        \App\Models\DronePosition::create([
            'drone_id' => $data['drone_id'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Position stored']);
    }

    /* public function getLatest()
    {
        // Пример: возвращаем координаты первого дрона
        return response()->json([
            'lat' => 50.4501,
            'lng' => 30.5234,
        ]);
    } */

    /**
     * Вернуть последние координаты одного дрона (по ID, например).
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
