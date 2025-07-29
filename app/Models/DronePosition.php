<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DronePosition extends Model
{
    public $timestamps = false; // используем только created_at

    protected $fillable = ['drone_id', 'lat', 'lng', 'created_at'];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
