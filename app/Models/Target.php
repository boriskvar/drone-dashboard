<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Drone;

class Target extends Model
{
    protected $fillable = ['drone_id', 'lat', 'lng'];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}
