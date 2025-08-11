<?php

namespace App\Http\Controllers\Admin\Simulate;

use App\Http\Controllers\Controller;
use App\Models\Simulate\SimulateTarget;
use App\Models\Drone;
use Illuminate\Http\Request;

class AdminSimulateTargetController extends Controller
{
    public function index()
    {
        $targets = SimulateTarget::with('drone')->latest()->paginate(20);

        return view('admin.simulate_targets.index', [
            'targets' => $targets,
            'activeRoute' => 'admin.simulate_targets.index',
        ]);
    }

    public function create()
    {
        $drones = Drone::all();

        return view('admin.simulate_targets.create', [
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_targets.create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Если цель уже есть — обновим, иначе создадим новую
        SimulateTarget::updateOrCreate(
            ['drone_id' => $validated['drone_id']],
            ['latitude' => $validated['latitude'], 'longitude' => $validated['longitude']]
        );

        return redirect()->route('admin.simulate_targets.index')->with('success', 'Цель назначена.');
    }

    public function edit(SimulateTarget $simulateTarget)
    {
        $drones = Drone::all();

        return view('admin.simulate_targets.edit', [
            'target' => $simulateTarget,
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_targets.index',
        ]);
    }

    public function update(Request $request, SimulateTarget $simulateTarget)
    {
        $validated = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $simulateTarget->update($validated);

        return redirect()->route('admin.simulate_targets.index')->with('success', 'Цель обновлена.');
    }

    public function destroy(SimulateTarget $simulateTarget)
    {
        $simulateTarget->delete();

        return redirect()->route('admin.simulate_targets.index')->with('success', 'Цель удалена.');
    }
}