<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SimulateDroneFlight;
use App\Models\Drone;


class AdminSimulationController extends Controller
{
    // Запуск симуляции
    public function start(Drone $drone)
    {
        // Запускаем симуляцию для дрона через Job/Command
        dispatch(new SimulateDroneFlight($drone->id));

        return redirect()->back()->with('success', "Симуляция дрона {$drone->id} запущена");
    }

    // Остановка симуляции
    public function stop(Drone $drone)
    {
        // Останавливаем симуляцию через Job/Command
        // dispatch(new StopDroneSimulation($drone->id));

        // Для простоты пока не реализуем явную остановку
        return redirect()->back()->with('success', "Симуляция дрона {$drone->id} можно будет остановить позже");
    }
}