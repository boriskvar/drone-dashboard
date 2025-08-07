<?php

namespace App\Models\Simulate;

use Illuminate\Database\Eloquent\Model;
use App\Models\Drone;

class SimulateTarget extends Model
{
    protected $fillable = ['drone_id', 'latitude', 'longitude', 'assigned_at'];

    public function drone()
    {
        return $this->belongsTo(Drone::class);
    }
}