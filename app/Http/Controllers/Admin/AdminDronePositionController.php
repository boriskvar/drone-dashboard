<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Drone;
use App\Models\DronePosition;

class AdminDronePositionController extends Controller
{
    public function index()
    {
        $positions = DronePosition::with('drone')->latest()->paginate(20);
        return view('admin.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.positions.create', [
            'drones' => Drone::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        DronePosition::create($validated);

        return redirect()->route('admin.positions.index')->with('success', 'Позиция добавлена');
    }

    public function edit(DronePosition $position)
    {
        $drones = Drone::all();
        return view('admin.positions.edit', compact('position', 'drones'));
    }

    public function update(Request $request, DronePosition $position)
    {
        $validated = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $position->update($validated);

        return redirect()->route('admin.positions.index')->with('success', 'Позиция обновлена');
    }

    public function destroy(DronePosition $position)
    {
        $position->delete();
        return redirect()->route('admin.positions.index')->with('success', 'Позиция удалена');
    }
}