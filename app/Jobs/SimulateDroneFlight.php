<?php

namespace App\Jobs;


use App\Models\Drone;
use App\Models\FlightData;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SimulateDroneFlight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int ИД дрона */
    public int $droneId;

    /** @var int Интервал (сек) между точками */
    public int $interval;

    /**
     * Не дать воркеру прервать задачу слишком рано.
     * Подбери значение под свой сценарий (например, 1 час).
     */
    public int $timeout = 3600;

    /**
     * @param int $droneId  ID дрона
     * @param int $interval Интервал записи координат (сек)
     */
    public function __construct(int $droneId, int $interval = 3)
    {
        $this->droneId  = $droneId;   // сохраняем, чтобы достать дрона в handle()
        $this->interval = $interval;  // сколько «спать» между точками
    }

    /**
     * Главный метод — выполняется воркером очереди
     */
    public function handle(): void
    {


        // 1) Пытаемся найти дрона. Если удалён — выходим.
        $drone = Drone::find($this->droneId);
        /* if (!$drone) {
            return; // ничего делать
        } */
        if (!$drone) {
            Log::warning("Drone not found", ['id' => $this->droneId]);
            return;
        }

        /**
         * 2) Крутимся, пока симуляция включена.
         *    ВАЖНО: используем fresh() в условии и в теле — чтобы брать самые свежие данные из БД.
         *    Это позволяет контроллеру «стопнуть» полёт простым обновлением флага в БД.
         */
        while ($drone->fresh()->is_simulating) {
            // Берём ОЧЕНЬ свежие координаты на текущий тик
            $drone = $drone->fresh();

            // 3) Получаем стартовые координаты (если пусто — даём базовые для Киева)
            $lat = $drone->latitude  ?? 50.4501;
            $lng = $drone->longitude ?? 30.5234;

            // 4) Имитация небольшого сдвига
            $lat += random_int(-10, 10) * 0.0001;
            $lng += random_int(-10, 10) * 0.0001;

            // 5) Пишем «точку трека» в таблицу flight_data
            FlightData::create([
                'drone_id'    => $drone->id,
                'latitude'    => $lat,
                'longitude'   => $lng,
                'altitude'    => random_int(100, 300),
                'speed'       => random_int(50, 300),
                'heading'     => random_int(0, 360),
                'recorded_at' => now(),
            ]);

            // 🔍 Логируем для отладки
            Log::info("New flight data", [
                'drone' => $drone->id,
                'lat' => $lat,
                'lng' => $lng
            ]);

            // 6) Обновляем «текущее положение» дрона (удобно для карты)
            $drone->update([
                'latitude'  => $lat,
                'longitude' => $lng,
            ]);

            // 7) Пауза до следующего тика
            sleep($this->interval);
            // После паузы цикл снова проверит is_simulating через fresh()
        }

        // 8) Вышли из цикла — значит админ нажал «Стоп», или флаг стал false. Job заканчивается.
    }
}