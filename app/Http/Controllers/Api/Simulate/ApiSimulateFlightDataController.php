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
     * Получить координаты всех активных дронов с треками и целями
     */
    public function latestPositions(): JsonResponse
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
