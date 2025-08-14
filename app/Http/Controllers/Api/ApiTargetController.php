<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Target;

class ApiTargetController extends Controller
{
    // Назначить цель дрону (создать запись)
    public function store(Request $request)
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
}
