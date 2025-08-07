<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FlightData extends Model
{
    use HasFactory;

    // Явное указание таблицы (можно опустить, если имя модели и таблицы совпадают по Laravel naming convention)
    protected $table = 'flight_data';

    // Какие поля можно массово заполнять (для create(), update())
    protected $fillable = [
        'drone_id',
        'latitude',
        'longitude',
        'altitude',
        'speed',
        'heading',
        'recorded_at',
    ];

    // Отключать ли авто-таймстемпы (created_at, updated_at)
    public $timestamps = true;

    // Если используешь кастомное имя timestamp-поля (не обязательно)
    protected $dates = ['recorded_at'];

    // Связь с моделью Drone (если есть)
    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}