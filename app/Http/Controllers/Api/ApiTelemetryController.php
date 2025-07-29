<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Drone;
use App\Models\Telemetry;

class ApiTelemetryController extends Controller
{
    // Получить трек дрона
    public function track($id)
    {
        $telemetries = Telemetry::where('drone_id', $id)
            ->orderBy('created_at')
            ->get(['latitude', 'longitude', 'altitude', 'created_at']);

        return response()->json($telemetries);
    }

    // Сохранить координаты
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude' => 'nullable|numeric',
        ]);

        $telemetry = new Telemetry([
            'drone_id'  => $id,
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'altitude'  => $validated['altitude'] ?? null,
        ]);

        $telemetry->save();

        return response()->json(['status' => 'ok', 'id' => $telemetry->id]);
    }
}