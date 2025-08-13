<?php

namespace App\Http\Controllers\Admin\Simulate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Simulate\SimulateFlightData;
use App\Models\Drone;

class AdminSimulateFlightDataController extends Controller
{
    public function index()
    {
        $flights = SimulateFlightData::with('drone')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.simulate_flight_data.index', [
            'flights' => $flights,
            'activeRoute' => 'admin.simulate_flight_data.index',
        ]);
    }

    public function create()
    {
        $drones = Drone::all();

        return view('admin.simulate_flight_data.create', [
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_flight_data.create',
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

        SimulateFlightData::create($validated);

        return redirect()->route('admin.simulate_flight_data.index')
            ->with('success', 'Данные полёта добавлены.');
    }

    public function edit(SimulateFlightData $simulateFlightData)
    {
        $flight = $simulateFlightData; // $simulateFlightData — это уже модель из БД
        $drones = Drone::all();

        return view('admin.simulate_flight_data.edit', [
            'flight' => $flight,
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_flight_data.index',
        ]);
    }

    public function update(Request $request, SimulateFlightData $simulateFlightData)
    {
        $validated = $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        // $simulateFlightData — это уже модель из БД
        $flight = $simulateFlightData; // Assuming $simulateFlightData is the model instance
        $flight->update($validated);

        return redirect()->route('admin.simulate_flight_data.index')
            ->with('success', 'Данные полёта обновлены.');
    }

    public function destroy(SimulateFlightData $simulateFlightData)
    {
        // $simulateFlightData — это уже модель из БД
        $flight = $simulateFlightData; // Assuming $simulateFlightData is the model instance
        $flight->delete();

        return redirect()->route('admin.simulate_flight_data.index')
            ->with('success', 'Данные полёта удалены.');
    }
}