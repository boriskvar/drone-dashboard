<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, что пользователь аутентифицирован и является админом
        /*  if (! $request->user()?->is_admin) {
            abort(403, 'Доступ запрещён');
        } */

        if (! $request->user()?->isAdmin()) {
            abort(403, 'Доступ запрещён'); // или redirect('/dashboard')
        }

        return $next($request); // доступ разрешён
    }
}