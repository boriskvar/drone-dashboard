<?php

namespace App\Http\Controllers\Api;

use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiTargetController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $target = Target::updateOrCreate(
            ['drone_id' => $data['drone_id']],
            ['lat' => $data['lat'], 'lng' => $data['lng']]
        );

        return response()->json([
            'status' => 'ok',
            'message' => 'Цель успешно сохранена',
            'target' => $target,
        ], 201); // <== Явно укажем статус 201
    }
}
