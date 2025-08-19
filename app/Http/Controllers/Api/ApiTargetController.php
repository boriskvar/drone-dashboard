<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Target;
use App\Models\Drone;

class ApiTargetController extends Controller
{
    /**
     * 📍 Показать цель дрона
     * GET /api/drones/{drone}/target
     */
    public function show(Drone $drone): JsonResponse
    {
        return response()->json($drone->target);
    }

    /**
     * 🎯 Обновить или назначить цель дрону
     * PATCH /api/drones/{drone}/target
     */
    public function update(Request $request, Drone $drone): JsonResponse
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $target = Target::updateOrCreate(
            ['drone_id' => $drone->id],
            $validated
        );

        return response()->json([
            'message' => 'Цель успешно назначена',
            'target'  => $target,
        ]);
    }
}