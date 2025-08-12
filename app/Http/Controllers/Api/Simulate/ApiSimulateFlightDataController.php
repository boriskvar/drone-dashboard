<?php

namespace App\Http\Controllers\Api\Simulate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Simulate\SimulateFlightData;
use App\Models\Simulate\SimulateTarget;
use App\Models\Drone;

class ApiSimulateFlightDataController extends Controller
{
    /**
     * Получить трек дрона по drone_id
     */
    public function track(int $droneId): JsonResponse
    {
        $positions = SimulateFlightData::where('drone_id', $droneId)
            ->orderBy('created_at')
            ->get(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

        return response()->json($positions);
    }

    /**
     * Сохранить координаты (создать новую позицию) по drone_id
     */
    public function store(Request $request, int $droneId): JsonResponse
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        $position = SimulateFlightData::create([
            'drone_id'  => $droneId,
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'altitude'  => $validated['altitude'] ?? null,
            'speed'     => $validated['speed'] ?? null,
            'heading'   => $validated['heading'] ?? null,
        ]);

        return response()->json(['status' => 'ok', 'id' => $position->id]);
    }

    /**
     * Обновить позицию по id
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $position = SimulateFlightData::findOrFail($id);

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

    /**
     * Удалить позицию по id
     */
    public function destroy(int $id): JsonResponse
    {
        $position = SimulateFlightData::findOrFail($id);
        $position->delete();

        return response()->json(['status' => 'deleted']);
    }

    /**
     * Получить координаты всех активных дронов с треками и целями
     */
    public function coordinates(): JsonResponse
    {
        $drones = Drone::all();

        $result = [];

        foreach ($drones as $drone) {
            $track = SimulateFlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['latitude', 'longitude'])
                ->reverse()
                ->values();

            $last = SimulateFlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->first(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

            $target = SimulateTarget::where('drone_id', $drone->id)->first(['latitude', 'longitude']);

            if ($last) {
                $result[] = [
                    'id'         => $drone->id,
                    'name'       => $drone->name,
                    'latitude'   => $last->latitude,
                    'longitude'  => $last->longitude,
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
