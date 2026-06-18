<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Grupo;
use App\Models\Documento;
use App\Models\Entrevistado;

class InformeGrupos extends Component
{
    use WithFileUploads;

    public string $filtro_estado   = 'activo';
    public ?int $subiendo_grupo_id = null;
    public $devolucion_final;

    public array $condicion = [];

    public function mount(): void
    {
        $grupos = Grupo::with('usuarios')->get();
        foreach ($grupos as $grupo) {
            foreach ($grupo->usuarios as $alumno) {
                $this->condicion[$grupo->id][$alumno->id] =
                    $alumno->pivot->condicion ?? '';
            }
        }
    }

    public function guardarCondicion(int $grupo_id, int $user_id): void
    {
        $valor = $this->condicion[$grupo_id][$user_id] ?? null;

        \DB::table('grupo_usuario')
            ->where('grupo_id', $grupo_id)
            ->where('user_id', $user_id)
            ->update(['condicion' => $valor ?: null]);

        session()->flash('mensaje_' . $grupo_id . '_' . $user_id, 'Guardado.');
    }

    public function finalizarGrupo(int $grupo_id): void
    {
        Grupo::find($grupo_id)?->update(['estado' => 'finalizado']);
        session()->flash('mensaje', 'Grupo finalizado correctamente.');
    }

    public function reactivarGrupo(int $grupo_id): void
    {
        Grupo::find($grupo_id)?->update(['estado' => 'activo']);
    }

    public function abrirSubidaDevolucion(int $grupo_id): void
    {
        $this->subiendo_grupo_id = $grupo_id;
        $this->devolucion_final  = null;
    }

    public function subirDevolucionFinal(): void
    {
        $this->validate([
            'devolucion_final' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'],
        ]);

        $grupo     = Grupo::find($this->subiendo_grupo_id);
        $extension = $this->devolucion_final->getClientOriginalExtension();
        $nombre    = \Str::slug('devolucion-final-' . $grupo->nombre . '-' . now()->format('YmdHis')) . '.' . $extension;
        $path      = $this->devolucion_final->storeAs('devoluciones-finales', $nombre, 'uploads');

        $grupo->update(['devolucion_final_path' => $path]);

        $this->subiendo_grupo_id = null;
        $this->devolucion_final  = null;
        session()->flash('mensaje', 'Devolución final subida correctamente.');
    }

    public function render()
    {
        $grupos = Grupo::with([
                'caso',
                'usuarios',
                'solicitudes' => fn($q) => $q->where('estado', 'aprobada'),
                'entregas'    => fn($q) => $q->whereNotNull('nota'),
            ])
            ->when($this->filtro_estado !== 'todos', fn($q) => $q->where('estado', $this->filtro_estado))
            ->orderBy('nombre')
            ->get();

        $documentos_por_caso    = Documento::where('activo', true)->get()->groupBy('caso_id');
        $entrevistados_por_caso = Entrevistado::where('activo', true)->get()->groupBy('caso_id');

        return view('livewire.docente.informe-grupos', [
            'grupos'                 => $grupos,
            'documentos_por_caso'    => $documentos_por_caso,
            'entrevistados_por_caso' => $entrevistados_por_caso,
        ]);
    }
}
