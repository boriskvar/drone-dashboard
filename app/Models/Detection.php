<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Detection extends Model
{
    use HasFactory;

    // Разрешённые для массового заполнения поля
    protected $fillable = [
        'image_id',
        'target',
        'x1',
        'y1',
        'x2',
        'y2',
    ];

    /**
     * Связь с картинкой
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}