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
    public function index()
    {
        return view('operator.index', [
            'menuItems' => $this->getMenuItems(),
            // 'activeTitle' => 'Панель оператора'
            'activeRoute' => 'operator.map'
        ]);
    }

    public function map()
    {
        return view('operator.map', [
            'menuItems' => $this->getMenuItems(),
            'activeTitle' => 'Карта дронов'
        ]);
    }

    private function getMenuItems(): array
    {
        return [
            [
                'title' => 'Панель оператора',
                'url' => route('operator.index'),
                'route' => 'operator.index'
            ],
            [
                'title' => 'Карта дронов',
                'url' => route('operator.map'),
                'route' => 'operator.map'
            ]
        ];
    }
}