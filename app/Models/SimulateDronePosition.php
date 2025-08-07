<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SimulateDronePosition extends Model
{
    use HasFactory;

    // Имя таблицы (не обязательно, если Laravel сам определит верно)
    protected $table = 'simulate_drone_positions';

    // Разрешённые поля для массового заполнения
    protected $fillable = [
        'drone_id',
        'latitude',
        'longitude',
        'altitude',
        'speed',
        'heading',
        'recorded_at',
    ];

    // Автоматическое преобразование даты
    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    // Если нужна связь с моделью Drone (опционально)
    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}