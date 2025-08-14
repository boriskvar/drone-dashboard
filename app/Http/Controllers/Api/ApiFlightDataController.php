<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\FlightData;
use App\Models\Target;
use App\Models\Drone;

class ApiFlightDataController extends Controller
{

    /**
     * Получить координаты всех активных дронов с треками и целями
     */
    public function latestPositions(): JsonResponse
    {
        $drones = Drone::all();

        $result = [];

        foreach ($drones as $drone) {
            $track = FlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->limit(20)
                ->get(['latitude', 'longitude'])
                ->reverse()
                ->values();

            $last = FlightData::where('drone_id', $drone->id)
                ->orderByDesc('created_at')
                ->first(['latitude', 'longitude', 'altitude', 'speed', 'heading', 'created_at']);

            $target = Target::where('drone_id', $drone->id)->first(['latitude', 'longitude']);

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