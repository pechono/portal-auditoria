<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EsAlumno
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {          
        if (!Auth::check() || Auth::user()->rol !== 'alumno') {
            abort(403, 'Acceso no autorizado');
        }
        return $next($request);
    }
}
