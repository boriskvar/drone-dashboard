<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Target;
use App\Models\Drone;
use Illuminate\Http\Request;

class AdminTargetController extends Controller
{
    public function index()
    {
        $targets = Target::with('drone')->latest()->paginate(20);

        return view('admin.targets.index', [
            'targets' => $targets,
            'activeRoute' => 'admin.targets.index',
        ]);
    }

    public function create()
    {
        $drones = Drone::all();

        return view('admin.targets.create', [
            'drones' => $drones,
            'activeRoute' => 'admin.targets.create',
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
        Target::updateOrCreate(
            ['drone_id' => $validated['drone_id']],
            ['latitude' => $validated['latitude'], 'longitude' => $validated['longitude']]
        );

        return redirect()->route('admin.targets.index')->with('success', 'Цель назначена.');
    }

    public function edit(Target $target)
    {
        $drones = Drone::all();

        return view('admin.targets.edit', [
            'target' => $target,
            'drones' => $drones,
            'activeRoute' => 'admin.targets.index',
        ]);
    }


    public function update(Request $request, Target $target)
    {
        $validated = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $target->update($validated);

        return redirect()->route('admin.targets.index')->with('success', 'Цель обновлена.');
    }

    public function destroy(Target $target)
    {
        $target->delete();

        return redirect()->route('admin.targets.index')->with('success', 'Цель удалена.');
    }
}
