<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    // Разрешённые для массового заполнения поля
    protected $fillable = [
        'title',
        'path',
        'width',
        'height',
    ];

    /**
     * Опциональная связь с детекциями (например, результаты обработки)
     */
    public function detections()
    {
        return $this->hasMany(Detection::class);
    }
}