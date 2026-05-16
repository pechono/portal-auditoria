<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // ← agregás este use

class EsDocente
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->rol !== 'docente') {
            abort(403, 'Acceso no autorizado');
        }

        return $next($request);
    }
}