<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Drone;
use Illuminate\Http\JsonResponse;

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
    public function index(): JsonResponse
    {
        $drones = Drone::select('id', 'lat', 'lng')->get();
        return response()->json($drones);
    }

    /**
     * Сохранить телеметрию от дрона.
     */
    public function store(Request $request): JsonResponse
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