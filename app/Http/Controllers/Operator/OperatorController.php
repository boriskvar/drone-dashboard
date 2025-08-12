<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    /**
     * Display the operator dashboard.
     *
     *  @return \Illuminate\View\View
     */
    /* public function index()
    {
        return view('operator.index', [
            'menuItems' => $this->getMenuItems(),
            // 'activeTitle' => 'Панель оператора'
            'activeRoute' => 'operator.map'
        ]);
    } */

    public function map()
    {
        /* $drones = [
            ['id' => 1, 'name' => 'Drone A', 'lat' => 50.45, 'lng' => 30.52],
            ['id' => 2, 'name' => 'Drone B', 'lat' => 50.46, 'lng' => 30.53],
        ]; */

        return view('operator.map', [
            // 'drones' => $drones,
            'menuItems' => $this->getMenuItems(), // ✅ общее меню
            'activeRoute' => 'operator.map'       // ✅ текущий активный
        ]);
    }

    private function getMenuItems(): array
    {
        return [
            // [
            //     'title' => 'Панель оператора',
            //     'url' => route('operator.index'),
            //     'route' => 'operator.index'
            // ],
            [
                'title' => 'Карта дронов',
                'url' => route('operator.map'),
                'route' => 'operator.map'
            ]
        ];
    }
}