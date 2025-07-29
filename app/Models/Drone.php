<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    protected $fillable = [
        'name',
        'lat',
        'lng',
        'status',
        'serial_number',
        'model',
        'manufacturer',
        'manufacture_date',
        'firmware_version',
    ];

    public function positions()
    {
        return $this->hasMany(DronePosition::class);
    }
}