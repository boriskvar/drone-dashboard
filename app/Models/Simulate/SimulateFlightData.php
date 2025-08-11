<?php

namespace App\Models\Simulate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SimulateFlightData extends Model
{
    use HasFactory;

    protected $table = 'simulate_flight_data';

    protected $fillable = [
        'drone_id',
        'latitude',
        'longitude',
        'altitude',
        'speed',
        'heading',
        'recorded_at',
    ];

    protected $casts = [
        'latitude'    => 'float',
        'longitude'   => 'float',
        'altitude'    => 'float',
        'speed'       => 'float',
        'heading'     => 'float',
        'recorded_at' => 'datetime',
    ];

    public function drone()
    {
        return $this->belongsTo(\App\Models\Drone::class);
    }
}