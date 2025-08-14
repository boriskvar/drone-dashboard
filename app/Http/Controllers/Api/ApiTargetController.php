<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Target;

class ApiTargetController extends Controller
{
    // Назначить цель дрону (создать запись)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'drone_id'    => 'required|exists:drones,id',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
        ]);

        $target = Target::updateOrCreate(
            ['drone_id' => $validated['drone_id']],
            [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]
        );

        return response()->json([
            'message' => 'Цель успешно назначена',
            'target' => $target,
        ], 201);
    }

    public function update(Request $request, $drone): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $target = Target::updateOrCreate(
            ['drone_id' => $drone],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return response()->json($target);
    }
}
