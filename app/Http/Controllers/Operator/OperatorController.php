<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @method ControllerMiddlewareOptions middleware(string|array $middleware)
 */

class OperatorController extends Controller
{
    /**
     * Display the operator dashboard.
     *
     *  @return \Illuminate\View\View
     */
    public function index()
    {
        $menuItems = [
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

        return view('operator.index', [
            'menuItems' => $menuItems,
            'activeTitle' => 'Панель оператора'
        ]);
    }

    public function map()
    {
        $menuItems = [
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

        return view('operator.map', [
            'menuItems' => $menuItems,
            'activeTitle' => 'Карта дронов'
        ]);
    }
}