<?php

namespace App\Jobs;

use App\Models\Drone;
use App\Models\FlightData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SimulateDroneFlight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $droneId;
    public $interval; // секунда между координатами

    /**
     * @param int $droneId - id дрона
     * @param int $interval - интервал записи координат
     */
    public function __construct(int $droneId, int $interval = 3)
    {
        $this->droneId = $droneId;
        $this->interval = $interval;
    }

    public function handle()
    {
        $drone = Drone::find($this->droneId);
        if (!$drone) return;

        // Берём текущие координаты или случайные стартовые
        $lat = $drone->latitude ?? 50.4501;
        $lng = $drone->longitude ?? 30.5234;

        // Простейшая имитация движения — небольшой случайный сдвиг
        $lat += rand(-10, 10) * 0.0001;
        $lng += rand(-10, 10) * 0.0001;

        // Запись в flight_data
        FlightData::create([
            'drone_id'  => $drone->id,
            'latitude'  => $lat,
            'longitude' => $lng,
            'altitude'  => rand(100, 300), // пример
            'speed'     => rand(50, 300),
            'heading'   => rand(0, 360),
            'recorded_at' => now(),
        ]);

        // Обновляем позицию дрона (опционально)
        $drone->update(['latitude' => $lat, 'longitude' => $lng]);

        // Повторяем через интервал
        dispatch(new self($this->droneId, $this->interval))
            ->delay(now()->addSeconds($this->interval));
    }
}