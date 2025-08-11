<?php

namespace App\Http\Controllers\Admin\Simulate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Simulate\SimulateTrack;
use App\Models\Drone;

class AdminSimulateTrackController extends Controller
{
    public function index()
    {
        $tracks = SimulateTrack::with('drone')->latest()->paginate(20);

        return view('admin.simulate_tracks.index', [
            'simulateTracks' => $tracks,
            'activeRoute' => 'admin.simulate_tracks.index',
        ]);
    }

    public function create()
    {
        $drones = Drone::all();

        return view('admin.simulate_tracks.create', [
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_tracks.create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        SimulateTrack::create($validated);

        return redirect()->route('admin.simulate_tracks.index')
            ->with('success', 'Трек добавлен.');
    }

    public function edit(SimulateTrack $simulateTrack)
    {
        $drones = Drone::all();

        return view('admin.simulate_tracks.edit', [
            'simulateTrack' => $simulateTrack,
            'drones' => $drones,
            'activeRoute' => 'admin.simulate_tracks.index',
        ]);
    }

    public function update(Request $request, SimulateTrack $simulateTrack)
    {
        $validated = $request->validate([
            'drone_id' => 'required|exists:drones,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'heading' => 'nullable|numeric',
        ]);

        $simulateTrack->update($validated);

        return redirect()->route('admin.simulate_tracks.index')
            ->with('success', 'Трек обновлен.');
    }

    public function destroy(SimulateTrack $simulateTrack)
    {
        $simulateTrack->delete();

        return redirect()->route('admin.simulate_tracks.index')
            ->with('success', 'Трек удален.');
    }
}