<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Entrega;
use App\Models\Grupo;

class GestionEntregas extends Component
{
    use WithFileUploads;

    public bool $mostrarModal    = false;
    public int $entrega_id       = 0;
    public string $estado        = '';
    public string $comentario    = '';
    public string $filtro_estado = 'enviada';
    public string $vista         = 'grupos';
    public $devolucion;
    public ?float $nota          = null;

    protected function rules(): array
    {
        return [
            'estado'     => ['required', 'in:aprobada,con_observaciones,rechazada'],
            'comentario' => ['nullable', 'string', 'max:1000'],
            'devolucion' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'nota'       => ['nullable', 'numeric', 'min:0', 'max:10'],
        ];
    }

    protected function messages(): array
    {
        return [
            'estado.required' => 'Debe seleccionar un estado.',
            'nota.min'        => 'La nota mínima es 0.',
            'nota.max'        => 'La nota máxima es 10.',
        ];
    }

    public function abrirModal(int $entrega_id): void
    {
        $this->reset(['comentario', 'devolucion', 'nota', 'estado']);
        $this->entrega_id = $entrega_id;

        $entrega = Entrega::find($entrega_id);
        if ($entrega && $entrega->estado !== 'enviada') {
            $this->estado     = $entrega->estado;
            $this->comentario = $entrega->comentario_docente ?? '';
            $this->nota       = $entrega->nota;
        }

        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    public function procesarEntrega(): void
    {
        $this->validate();

        $entrega = Entrega::find($this->entrega_id);

        $devolucion_path = $entrega->devolucion_path;


        if ($this->devolucion) {
        $extension       = $this->devolucion->getClientOriginalExtension();
        $nombre_original = pathinfo($this->devolucion->getClientOriginalName(), PATHINFO_FILENAME);
        $nombre          = \Str::slug($nombre_original) . '-' . now()->format('Y-m-d-His') . '.' . $extension;
        $devolucion_path = $this->devolucion->storeAs('devoluciones', $nombre, 'uploads');
        }

        $entrega->update([
            'estado'             => $this->estado,
            'comentario_docente' => $this->comentario,
            'devolucion_path'    => $devolucion_path,
            'nota'               => $this->nota,
            'revisado_por'       => auth()->id(),
            'revisado_at'        => now(),
        ]);


                // Notificar al alumno
        $entrega = Entrega::find($this->entrega_id);
        foreach ($entrega->grupo->usuarios as $alumno) {
            \App\Models\Notificacion::create([
                'user_id' => $alumno->id,
                'tipo'    => 'entrega_' . $this->estado,
                'mensaje' => "Tu entrega de la etapa \"{$entrega->etapa->nombre}\" fue revisada con estado: " . ucfirst(str_replace('_', ' ', $this->estado)) . ". {$this->comentario}",
                'leida'   => false,
            ]);
}

        $this->cerrarModal();
        session()->flash('mensaje', 'Entrega revisada correctamente.');
    }

    public function render()
    {
        $entregas = Entrega::with(['grupo.caso', 'etapa'])
            ->where('estado', $this->filtro_estado)
            ->orderBy('created_at', 'desc')
            ->get();

        // Total de etapas por caso para calcular progreso
        $etapas_por_caso = \App\Models\Etapa::all()->groupBy('caso_id');

        // Vista por grupo: todos los grupos con progreso completo
        $grupos = Grupo::with([
                'caso',
                'entregas.etapa',
            ])
            ->whereHas('entregas')
            ->orderBy('nombre')
            ->get()
            ->map(function ($grupo) use ($etapas_por_caso) {
                $total_etapas = $etapas_por_caso->get($grupo->caso_id, collect())->count();

                // Etapa más avanzada entregada (por número de etapa)
                $max_etapa = $grupo->entregas->max(fn($e) => $e->etapa->numero ?? 0);

                // Etapa 1 es informativa y siempre cuenta como completada
                $pct = $total_etapas > 0 ? round($max_etapa / $total_etapas * 100) : 0;

                $pendientes = $grupo->entregas->where('estado', 'enviada')->count();
                $con_obs    = $grupo->entregas->where('estado', 'con_observaciones')->count();
                $rechazadas = $grupo->entregas->where('estado', 'rechazada')->count();
                $aprobadas  = $grupo->entregas->where('estado', 'aprobada')->count();

                $grupo->_total_etapas = $total_etapas;
                $grupo->_max_etapa    = $max_etapa;
                $grupo->_aprobadas    = $aprobadas;
                $grupo->_pendientes   = $pendientes;
                $grupo->_con_obs      = $con_obs;
                $grupo->_rechazadas   = $rechazadas;
                $grupo->_pct          = $pct;
                return $grupo;
            });

        return view('livewire.docente.gestion-entregas', [
            'entregas' => $entregas,
            'grupos'   => $grupos,
        ]);
    }
}
