<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Simulate\SimulateFlightData;
use App\Models\Simulate\SimulateTrack;
use App\Models\Simulate\SimulateTarget;
use App\Models\Track;

class Drone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'latitude',
        'longitude',
        'model',
        'serial_number',
        'manufacturer',
        'manufacture_date',
        'firmware_version',
    ];

    // Константы статусов
    public const STATUS_ACTIVE      = 'active';
    public const STATUS_OFFLINE     = 'offline';
    public const STATUS_IDLE        = 'idle';
    public const STATUS_IN_MISSION  = 'in_mission';
    public const STATUS_MAINTENANCE = 'maintenance';

    /**
     * Получить список всех возможных статусов с переводами для форм
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE      => 'Активен',
            self::STATUS_OFFLINE     => 'Отключен',
            self::STATUS_IDLE        => 'Ожидает',
            self::STATUS_IN_MISSION  => 'В миссии',
            self::STATUS_MAINTENANCE => 'Обслуживание',
        ];
    }

    protected $casts = [
        'manufacture_date' => 'datetime:Y-m-d',
    ];

    /**
     * Реальные треки дрона
     */
    /* public function tracks()
    {
        return $this->hasMany(Track::class, 'drone_id');
    } */

    /**
     * Симуляция — полётные данные
     */
    public function simulateFlightData()
    {
        return $this->hasMany(SimulateFlightData::class, 'drone_id');
    }

    /**
     * Симуляция — треки
     */
    public function simulateTracks()
    {
        return $this->hasMany(SimulateTrack::class, 'drone_id');
    }

    /**
     * Симуляция — цели
     */
    public function simulateTargets()
    {
        return $this->hasMany(SimulateTarget::class, 'drone_id');
    }
}