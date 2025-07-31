<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Target;

class ApiTargetController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        Target::updateOrCreate(
            ['drone_id' => $data['drone_id']],
            ['lat' => $data['lat'], 'lng' => $data['lng']]
        );

        return response()->json(['status' => 'ok']);
    }
}
