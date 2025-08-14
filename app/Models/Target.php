<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Drone;

class Target extends Model
{
    protected $fillable = ['drone_id', 'latitude', 'longitude'];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}
