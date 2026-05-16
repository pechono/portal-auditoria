<?php

namespace App\Livewire\Alumno;

use Livewire\Component;
use App\Models\Entrega;
use App\Models\Solicitud;
use App\Models\Etapa;

class Dashboard extends Component
{
    public function render()
    {
        $grupo = auth()->user()->grupos()->with('caso')->first();

        if (!$grupo) {
            return view('livewire.alumno.dashboard', [
                'grupo'            => null,
                'etapas'           => collect(),
                'entregas'         => collect(),
                'solicitudes'      => collect(),
                'proxima_etapa'    => null,
            ]);
        }

        $etapas = Etapa::where('caso_id', $grupo->caso_id)
            ->orderBy('orden')
            ->get();

        $entregas = Entrega::where('grupo_id', $grupo->id)
            ->get()
            ->keyBy('etapa_id');

        $solicitudes = Solicitud::where('grupo_id', $grupo->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Próxima etapa sin entregar
        $proxima_etapa = $etapas->first(function ($etapa) use ($entregas) {
            if ($etapa->numero === 1) return false;
            $entrega = $entregas->get($etapa->id);
            return !$entrega || $entrega->estado !== 'aprobada';
        });

        return view('livewire.alumno.dashboard', [
            'grupo'         => $grupo,
            'etapas'        => $etapas,
            'entregas'      => $entregas,
            'solicitudes'   => $solicitudes,
            'proxima_etapa' => $proxima_etapa,
        ]);
    }
}