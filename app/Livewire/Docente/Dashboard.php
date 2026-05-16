<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\Solicitud;
use App\Models\Entrega;
use App\Models\User;

class Dashboard extends Component
{
    public function render()
    {
        $total_alumnos = User::where('rol', 'alumno')->count();
        $total_grupos  = Grupo::count();

        $solicitudes_pendientes = Solicitud::where('estado', 'pendiente')->count();
        $entregas_pendientes    = Entrega::where('estado', 'enviada')->count();

        $grupos_recientes = Grupo::with(['caso', 'usuarios'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $solicitudes_recientes = Solicitud::with(['grupo.caso', 'solicitante'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('livewire.docente.dashboard', [
            'total_alumnos'          => $total_alumnos,
            'total_grupos'           => $total_grupos,
            'solicitudes_pendientes' => $solicitudes_pendientes,
            'entregas_pendientes'    => $entregas_pendientes,
            'grupos_recientes'       => $grupos_recientes,
            'solicitudes_recientes'  => $solicitudes_recientes,
        ]);
    }
}