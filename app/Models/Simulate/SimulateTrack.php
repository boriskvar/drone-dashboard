<?php

namespace App\Models\Simulate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Drone;


class SimulateTrack extends Model
{
    use HasFactory;

    // Имя таблицы (не обязательно, если Laravel сам определит верно)
    protected $table = 'simulate_tracks';

    // Разрешённые поля для массового заполнения
    protected $fillable = [
        'drone_id',
        'latitude',
        'longitude',
        'altitude',
        'speed',
        'heading',
    ];

    // Автоматическое преобразование даты
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Если нужна связь с моделью Drone (опционально)
    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}