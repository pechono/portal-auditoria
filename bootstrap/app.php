<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'es.docente' => \App\Http\Middleware\EsDocente::class,
            'es.alumno'  => \App\Http\Middleware\EsAlumno::class,
        ]);

        $middleware->redirectUsersTo(function ($request) {
            if (auth()->check()) {
                if (auth()->user()->rol === 'docente') {
                    return route('docente.dashboard');
                }
                return route('alumno.dashboard');
            }
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();