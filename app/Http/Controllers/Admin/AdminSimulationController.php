<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Models\Drone;
use App\Jobs\SimulateDroneFlight;
use App\Http\Controllers\Controller;


class AdminSimulationController extends Controller
{

    public function simulation()
    {
        $drones = Drone::all();
        // dd($drones->toArray()); // Debugging line to check drones data

        return view('admin.simulation.index', compact('drones'));
    }

    // Запуск симуляции
    public function start(Drone $drone)
    {
        // Log::info('START sim', ['id' => $drone->id]);

        if ($drone->is_simulating) {
            return back()->with('warning', "Дрон {$drone->name} уже в симуляции");
        }

        // Критично: поднять флаг перед dispatch
        $drone->is_simulating = true;
        $drone->save();
        // dd($drone->toArray()); // Debugging line to check drone data
        // Log::info('DRONE updated', ['id' => $drone->id, 'is_simulating' => $drone->is_simulating]);

        dispatch(new SimulateDroneFlight($drone->id));

        return back()->with('success', "Симуляция дрона {$drone->name} запущена");
    }

    // Остановка симуляции
    public function stop(Drone $drone)
    {
        if (!$drone->is_simulating) {
            return back()->with('warning', "Дрон {$drone->name} уже остановлен");
        }

        // Опустить флаг — Job увидит это через fresh() и завершится
        $drone->is_simulating = false;
        $drone->save();

        return back()->with('success', "Симуляция дрона {$drone->name} остановлена");
    }
}