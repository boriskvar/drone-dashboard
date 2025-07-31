<?php

namespace App\Http\Controllers\Api;

use App\Models\Drone;
use App\Models\Target;
use App\Models\Telemetry;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiTelemetryController extends Controller
{
    // Получить трек дрона
    public function track($id)
    {
        $telemetries = Telemetry::where('drone_id', $id)
            ->orderBy('created_at')
            ->get(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

        return response()->json($telemetries);
    }

    // Сохранить координаты (создать новую запись)
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        $telemetry = new Telemetry([
            'drone_id'  => $id,
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'altitude'  => $validated['altitude'] ?? null,
            'speed'     => $validated['speed'] ?? null,
            'heading'   => $validated['heading'] ?? null,
        ]);

        $telemetry->save();

        return response()->json(['status' => 'ok', 'id' => $telemetry->id]);
    }

    // Обновить существующую запись телеметрии
    public function update(Request $request, $id)
    {
        $telemetry = Telemetry::findOrFail($id);

        $validated = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        $telemetry->update($validated);

        return response()->json(['status' => 'updated', 'id' => $telemetry->id]);
    }

    // Удалить запись телеметрии
    public function destroy($id)
    {
        $telemetry = Telemetry::findOrFail($id);
        $telemetry->delete();

        return response()->json(['status' => 'deleted']);
    }

    // Получить координаты всех активных дронов с треками


    public function coordinates(): JsonResponse
    {
        $activeDrones = Drone::where('status', 'active')->get();

        $result = [];

        foreach ($activeDrones as $drone) {
            // Берём трек (20 последних точек)
            $track = Telemetry::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['latitude as lat', 'longitude as lng'])
                ->reverse()
                ->values();

            // Берём последнюю точку (актуальную)
            $last = Telemetry::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->first(['latitude as lat', 'longitude as lng', 'altitude', 'speed', 'heading', 'created_at']);

            // Берём цель (если есть)
            $target = Target::where('drone_id', $drone->id)->first(['lat', 'lng']);

            if ($last) {
                $result[] = [
                    'id'         => $drone->id,
                    'name'       => $drone->name,
                    'lat'        => $last->lat,
                    'lng'        => $last->lng,
                    'altitude'   => $last->altitude,
                    'speed'      => $last->speed,
                    'heading'    => $last->heading,
                    'updated_at' => $last->created_at->toDateTimeString(),
                    'track'      => $track,
                    'target'     => $target, // null если нет цели
                ];
            }
        }

        return response()->json($result);
    }
}