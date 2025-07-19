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
        return view('operator.index');
        // return response()->json(['status' => 'operator']); // Упрощенный ответ для теста
    }
}
