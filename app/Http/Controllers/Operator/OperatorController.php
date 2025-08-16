<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

class OperatorController extends Controller
{
    /**
     * Показывает карту дронов для оператора
     */
    public function map()
    {
        $drones = [
            ['id' => 1, 'name' => 'Drone A', 'lat' => 50.45, 'lng' => 30.52],
            ['id' => 2, 'name' => 'Drone B', 'lat' => 50.46, 'lng' => 30.53],
        ];

        return view('operator.map', [
            'drones' => $drones,
            'menuItems' => [
                [
                    'title' => 'Карта дронов',
                    'url' => route('operator.map'),
                    'route' => 'operator.map'
                ]
            ],
            'activeRoute' => 'operator.map'
        ]);
    }
}