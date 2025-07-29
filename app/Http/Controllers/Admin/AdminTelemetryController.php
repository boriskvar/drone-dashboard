<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Telemetry;
use App\Models\Drone;

class AdminTelemetryController extends Controller
{
    public function index()
    {
        $telemetries = Telemetry::with('drone')->latest()->paginate(20);
        return view('admin.telemetries.index', [
            'telemetries' => $telemetries,
            'activeRoute' => 'admin.telemetries.index',
        ]);
    }

    public function create()
    {
        $drones = Drone::all();

        return view('admin.telemetries.create', [
            'drones' => $drones,
            'activeRoute' => 'admin.telemetries.create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
        ]);

        Telemetry::create($validated);

        return redirect()->route('admin.telemetries.index')
            ->with('success', 'Телеметрия успешно добавлена.');
    }

    public function edit($id)
    {
        $telemetry = Telemetry::findOrFail($id);
        $drones = Drone::all();

        return view('admin.telemetries.edit', [
            'telemetry' => $telemetry,
            'drones' => $drones,
            // 'activeRoute' => 'admin.telemetries.edit', // ⚠️ Не будет совпадать с route() — это нужно обсудить
            'activeRoute' => 'admin.telemetries.index', //подсветка при редактировании

        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
        ]);

        $telemetry = Telemetry::findOrFail($id);
        $telemetry->update($validated);

        return redirect()->route('admin.telemetries.index')
            ->with('success', 'Телеметрия обновлена.');
    }

    public function destroy($id)
    {
        $telemetry = Telemetry::findOrFail($id);
        $telemetry->delete();

        return redirect()->route('admin.telemetries.index')
            ->with('success', 'Телеметрия удалена.');
    }
}