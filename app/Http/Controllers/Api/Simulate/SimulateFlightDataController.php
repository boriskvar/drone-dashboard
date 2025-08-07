<?php

namespace App\Http\Controllers\Api\Simulate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Simulate\SimulateDronePosition;
use App\Models\Simulate\SimulateTarget;
use App\Models\Drone;

class SimulateFlightDataController extends Controller
{
    public function track($id)
    {
        $positions = SimulateDronePosition::where('drone_id', $id)
            ->orderBy('created_at')
            ->get(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

        return response()->json($positions);
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

        $position = new SimulateDronePosition([
            'drone_id'  => $id,
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'altitude'  => $validated['altitude'] ?? null,
            'speed'     => $validated['speed'] ?? null,
            'heading'   => $validated['heading'] ?? null,
        ]);

        $position->save();

        return response()->json(['status' => 'ok', 'id' => $position->id]);
    }

    // Обновить существующую запись телеметрии
    public function update(Request $request, $id)
    {
        $position = SimulateDronePosition::findOrFail($id);

        $validated = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        $position->update($validated);

        return response()->json(['status' => 'updated', 'id' => $position->id]);
    }

    public function destroy($id)
    {
        $position = SimulateDronePosition::findOrFail($id);
        $position->delete();

        return response()->json(['status' => 'deleted']);
    }

    // Получить координаты всех активных дронов с треками
    public function coordinates(): JsonResponse
    {
        $drones = Drone::all();

        $result = [];

        foreach ($drones as $drone) {
            $track = SimulateDronePosition::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['latitude as lat', 'longitude as lng'])
                ->reverse()
                ->values();

            // Берём последнюю точку (актуальную)
            $last = SimulateDronePosition::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->first(['latitude as lat', 'longitude as lng', 'altitude', 'speed', 'heading', 'created_at']);

            // Берём цель (если есть)
            $target = SimulateTarget::where('drone_id', $drone->id)->first(['latitude as lat', 'longitude as lng']);

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
                    'target'     => $target,
                ];
            }
        }

        return response()->json($result);
    }
}