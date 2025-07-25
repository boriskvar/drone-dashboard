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

    public function index(): JsonResponse
    {
        return response()->json(Drone::select('id', 'lat', 'lng')->get());
    }

    // Пример: принять телеметрию от дрона
    public function store(Request $request)
    {
        // TODO: валидация и логика сохранения
        return response()->json(['message' => 'Telemetry received'], 201);
    }

    public function getLatest()
    {
        // Пример: возвращаем координаты первого дрона
        return response()->json([
            'lat' => 50.4501,
            'lng' => 30.5234,
        ]);
    }
}
