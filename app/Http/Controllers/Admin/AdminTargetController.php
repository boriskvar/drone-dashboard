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
        $targets = Target::with('drone')->latest()->get();
        return view('admin.targets.index', compact('targets'));
    }

    public function create()
    {
        $drones = Drone::all();
        return view('admin.targets.create', compact('drones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        // Убедимся, что цель на этого дрона одна: заменим существующую
        Target::updateOrCreate(
            ['drone_id' => $data['drone_id']],
            ['lat' => $data['lat'], 'lng' => $data['lng']]
        );

        return redirect()->route('admin.targets.index')->with('success', 'Цель назначена');
    }

    public function edit($id)
    {
        $target = Target::findOrFail($id);
        $drones = Drone::all();
        return view('admin.targets.edit', compact('target', 'drones'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric'
        ]);

        Target::findOrFail($id)->update($data);
        return redirect()->route('admin.targets.index')->with('success', 'Цель обновлена');
    }

    public function destroy($id)
    {
        Target::findOrFail($id)->delete();
        return back()->with('success', 'Цель удалена');
    }
}