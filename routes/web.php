<?php

use Illuminate\Support\Facades\Route;

// Ruta raíz — redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// ─────────────────────────────────────────
// Panel del docente
// ─────────────────────────────────────────
Route::middleware(['auth', 'es.docente'])
    ->prefix('docente')
    ->name('docente.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('docente.dashboard');
    })->name('dashboard');

    Route::get('/grupos', function () {
        return view('docente.grupos');
    })->name('grupos');

    Route::get('/solicitudes', function () {
        return view('docente.solicitudes');
    })->name('solicitudes');

    Route::get('/entregas', function () {
        return view('docente.entregas');
    })->name('entregas');

    
   
    Route::get('/repositorio', function () {
        return view('docente.repositorio');
    })->name('repositorio');

    Route::get('/casos', function () {
        return view('docente.casos');
    })->name('casos');
    
    Route::get('/alumnos', function () {
        return view('docente.alumnos');
    })->name('alumnos');

    Route::get('/finales', function () {
        return view('docente.finales');
    })->name('finales');

});

// ─────────────────────────────────────────
// Panel del alumno
// ─────────────────────────────────────────
Route::middleware(['auth', 'es.alumno'])
    ->prefix('alumno')
    ->name('alumno.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('alumno.dashboard');
    })->name('dashboard');

    Route::get('/recursos', function () {
        return view('alumno.recursos');
     })->name('recursos');

    Route::get('/etapas', function () {
        return view('alumno.etapas');
     })->name('etapas');

     Route::post('/notificaciones/{id}/leer', function ($id) {
            \App\Models\Notificacion::where('id', $id)
                ->where('user_id', auth()->id())
                ->update(['leida' => true]);
            return back();
        })->name('notificaciones.leer');

});
require __DIR__.'/auth.php';
// Deshabilitar registro público

Route::get('/register', function () {
    return redirect()->route('login');
});
Route::post('/register', function () {
    return redirect()->route('login');
});



