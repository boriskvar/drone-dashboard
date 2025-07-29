<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drone;
use Illuminate\Http\Request;

class AdminDroneController extends Controller
{
    protected array $statusOptions = ['active', 'offline', 'idle', 'in_mission', 'maintenance'];

    public function index()
    {
        $drones = Drone::all();
        return view('admin.drones.index', compact('drones'));
    }

    public function create()
    {
        return view('admin.drones.create', [
            'statusOptions' => $this->statusOptions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'status' => 'required|in:' . implode(',', $this->statusOptions),
            'serial_number' => 'nullable|string|unique:drones,serial_number',
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'firmware_version' => 'nullable|string|max:255',
        ]);

        Drone::create($validated);

        return redirect()->route('admin.drones.index');
    }

    public function edit(Drone $drone)
    {
        return view('admin.drones.edit', [
            'drone' => $drone,
            'statusOptions' => $this->statusOptions
        ]);
    }

    public function update(Request $request, Drone $drone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'status' => 'required|in:' . implode(',', $this->statusOptions),
            'serial_number' => 'nullable|string|unique:drones,serial_number,' . $drone->id,
            'model' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'manufacture_date' => 'nullable|date',
            'firmware_version' => 'nullable|string|max:255',
        ]);

        $drone->update($validated);

        return redirect()->route('admin.drones.index');
    }

    public function destroy(Drone $drone)
    {
        $drone->delete();
        return redirect()->route('admin.drones.index');
    }

    public function move(Drone $drone)
    {
        // Имитация небольшого сдвига координат
        $drone->lat += (rand(-5, 5) / 10000);
        $drone->lng += (rand(-5, 5) / 10000);
        $drone->save();

        return redirect()->back()->with('success', "Дрон #{$drone->id} сдвинут.");
    }
}