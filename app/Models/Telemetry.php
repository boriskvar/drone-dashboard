<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Telemetry extends Model
{
    protected $fillable = [
        'drone_id',
        'latitude',
        'longitude',
        'altitude',
        'speed',
        'heading',
    ];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}