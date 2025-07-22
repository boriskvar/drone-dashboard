<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DroneApiController extends Controller
{
    // Пример: получить текущий статус дронов
    public function index()
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
    }

    // Пример: принять телеметрию от дрона
    public function store(Request $request)
    {
        // TODO: валидация и логика сохранения
        return response()->json(['message' => 'Telemetry received'], 201);
    }
}
