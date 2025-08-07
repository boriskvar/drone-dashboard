<?php

namespace App\Http\Controllers\Admin\Simulate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SimulateDronePosition;
use App\Models\Drone;

class AdminSimulatePositionController extends Controller
{
    public function index()
    {
        $positions = SimulateDronePosition::with('drone')->latest()->paginate(20);

        return view('admin.simulate_positions.index', [
            'positions' => $positions,
            'activeRoute' => 'admin.simulate_positions.index',
        ]);
    }

    public function create()
    {
        $drones = Drone::all();

        return view('admin.simulate_positions.create', [
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_positions.create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        SimulateDronePosition::create($validated);

        return redirect()->route('admin.simulate_positions.index')
            ->with('success', 'Позиция успешно добавлена.');
    }

    public function edit($id)
    {
        $position = SimulateDronePosition::findOrFail($id);
        $drones = Drone::all();

        return view('admin.simulate_positions.edit', [
            'position' => $position,
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_positions.index', // чтобы подсвечивался пункт меню
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'drone_id'  => 'required|exists:drones,id',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
        ]);

        $position = SimulateDronePosition::findOrFail($id);
        $position->update($validated);

        return redirect()->route('admin.simulate_positions.index')
            ->with('success', 'Позиция обновлена.');
    }

    public function destroy($id)
    {
        $position = SimulateDronePosition::findOrFail($id);
        $position->delete();

        return redirect()->route('admin.simulate_positions.index')
            ->with('success', 'Позиция удалена.');
    }
}