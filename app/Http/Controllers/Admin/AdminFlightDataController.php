<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlightData;
use App\Models\Drone;

class AdminFlightDataController extends Controller
{
    public function index()
    {
        $flights = FlightData::with('drone')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.flight_data.index', [
            'flights' => $flights,
            'activeRoute' => 'admin.flight_data.index',
        ]);
    }

    public function create()
    {
        $drones = Drone::all();

        return view('admin.flight_data.create', [
            'drones' => $drones,
            'activeRoute' => 'admin.flight_data.create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        FlightData::create($validated);

        return redirect()->route('admin.flight_data.index')
            ->with('success', 'Данные полёта добавлены.');
    }

    public function edit(FlightData $flightData)
    {
        $flight = $flightData; // $flightData — это уже модель из БД
        $drones = Drone::all();

        return view('admin.flight_data.edit', [
            'flight' => $flight,
            'drones' => $drones,
            'activeRoute' => 'admin.flight_data.index',
        ]);
    }

    public function update(Request $request, FlightData $flightData)
    {
        $validated = $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        // $flightData — это уже модель из БД
        $flight = $flightData; // Assuming $flightData is the model instance
        $flight->update($validated);

        return redirect()->route('admin.flight_data.index')
            ->with('success', 'Данные полёта обновлены.');
    }

    public function destroy(FlightData $flightData)
    {
        // $flightData — это уже модель из БД
        $flight = $flightData; // Assuming $flightData is the model instance
        $flight->delete();

        return redirect()->route('admin.flight_data.index')
            ->with('success', 'Данные полёта удалены.');
    }
}