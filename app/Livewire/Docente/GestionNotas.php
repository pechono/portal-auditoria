<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\CicloLectivo;
use App\Models\TrabajoEvaluable;
use App\Models\NotaAlumno;
use App\Models\User;
use App\Models\Grupo;

class GestionNotas extends Component
{
    // Ciclo
    public ?int $ciclo_id         = null;
    public bool $mostrarModalCiclo = false;
    public string $ciclo_nombre   = '';
    public string $ciclo_anio     = '';
    public string $ciclo_obs      = '';
    public ?int $editando_ciclo   = null;

    // Trabajo evaluable
    public bool $mostrarModalTrabajo = false;
    public string $trabajo_nombre    = '';
    public ?int $editando_trabajo    = null;

    // Notas (grilla: [trabajo_evaluable_id][user_id] => nota)
    public array $notas        = [];
    public array $observaciones = [];

    public function mount(): void
    {
        $activo = CicloLectivo::where('activo', true)->latest()->first();
        if ($activo) {
            $this->ciclo_id = $activo->id;
            $this->cargarNotas();
        }
    }

    // ── Ciclos ─────────────────────────────────────
    public function abrirModalCiclo(?int $id = null): void
    {
        $this->reset(['ciclo_nombre', 'ciclo_anio', 'ciclo_obs', 'editando_ciclo']);
        $this->ciclo_anio = (string) date('Y');

        if ($id) {
            $c = CicloLectivo::find($id);
            $this->ciclo_nombre   = $c->nombre;
            $this->ciclo_anio     = (string) $c->anio;
            $this->ciclo_obs      = $c->observaciones ?? '';
            $this->editando_ciclo = $id;
        }

        $this->mostrarModalCiclo = true;
    }

    public function guardarCiclo(): void
    {
        $this->validate([
            'ciclo_nombre' => ['required', 'string', 'max:100'],
            'ciclo_anio'   => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        if ($this->editando_ciclo) {
            CicloLectivo::find($this->editando_ciclo)->update([
                'nombre'        => $this->ciclo_nombre,
                'anio'          => $this->ciclo_anio,
                'observaciones' => $this->ciclo_obs,
            ]);
        } else {
            $ciclo = CicloLectivo::create([
                'nombre'        => $this->ciclo_nombre,
                'anio'          => $this->ciclo_anio,
                'observaciones' => $this->ciclo_obs,
                'activo'        => true,
            ]);
            $this->ciclo_id = $ciclo->id;
            $this->cargarNotas();
        }

        $this->mostrarModalCiclo = false;
        session()->flash('mensaje', 'Ciclo guardado.');
    }

    public function seleccionarCiclo(int $id): void
    {
        $this->ciclo_id = $id;
        $this->cargarNotas();
    }

    // ── Trabajos evaluables ─────────────────────────
    public function abrirModalTrabajo(?int $id = null): void
    {
        $this->reset(['trabajo_nombre', 'editando_trabajo']);

        if ($id) {
            $t = TrabajoEvaluable::find($id);
            $this->trabajo_nombre   = $t->nombre;
            $this->editando_trabajo = $id;
        }

        $this->mostrarModalTrabajo = true;
    }

    public function guardarTrabajo(): void
    {
        $this->validate(['trabajo_nombre' => ['required', 'string', 'max:100']]);

        if ($this->editando_trabajo) {
            TrabajoEvaluable::find($this->editando_trabajo)->update(['nombre' => $this->trabajo_nombre]);
        } else {
            $orden = TrabajoEvaluable::where('ciclo_lectivo_id', $this->ciclo_id)->max('orden') + 1;
            TrabajoEvaluable::create([
                'ciclo_lectivo_id' => $this->ciclo_id,
                'nombre'           => $this->trabajo_nombre,
                'orden'            => $orden,
            ]);
        }

        $this->mostrarModalTrabajo = false;
        $this->cargarNotas();
    }

    public function eliminarTrabajo(int $id): void
    {
        TrabajoEvaluable::find($id)?->delete();
        $this->cargarNotas();
    }

    // ── Notas ───────────────────────────────────────
    private function cargarNotas(): void
    {
        if (!$this->ciclo_id) return;

        $registros = NotaAlumno::where('ciclo_lectivo_id', $this->ciclo_id)->get();

        $this->notas        = [];
        $this->observaciones = [];

        foreach ($registros as $r) {
            $this->notas[$r->trabajo_evaluable_id][$r->user_id]         = $r->nota !== null ? (string) $r->nota : '';
            $this->observaciones[$r->trabajo_evaluable_id][$r->user_id] = $r->observaciones ?? '';
        }
    }

    public function guardarNota(int $trabajo_id, int $user_id): void
    {
        $nota = $this->notas[$trabajo_id][$user_id] ?? null;
        $obs  = $this->observaciones[$trabajo_id][$user_id] ?? null;

        if ($nota !== null && $nota !== '') {
            $this->validate([
                "notas.{$trabajo_id}.{$user_id}" => ['nullable', 'numeric', 'min:0', 'max:10'],
            ]);
        }

        $alumno = User::find($user_id);
        $grupo  = $alumno?->grupos()->latest()->first();

        NotaAlumno::updateOrCreate(
            [
                'ciclo_lectivo_id'    => $this->ciclo_id,
                'user_id'             => $user_id,
                'trabajo_evaluable_id' => $trabajo_id,
            ],
            [
                'nota'         => ($nota !== '' && $nota !== null) ? $nota : null,
                'observaciones'=> $obs ?: null,
                'grupo_id'     => $grupo?->id,
                'caso_id'      => $grupo?->caso_id,
            ]
        );
    }

    public function render()
    {
        $ciclos = CicloLectivo::orderBy('anio', 'desc')->get();

        $ciclo    = $this->ciclo_id ? CicloLectivo::with('trabajos')->find($this->ciclo_id) : null;
        $trabajos = $ciclo?->trabajos ?? collect();

        // Cargar notas desde DB en cada render
        if ($this->ciclo_id) {
            $this->notas         = [];
            $this->observaciones = [];
            $registros = NotaAlumno::where('ciclo_lectivo_id', $this->ciclo_id)->get();
            foreach ($registros as $r) {
                $this->notas[$r->trabajo_evaluable_id][$r->user_id]         = $r->nota !== null ? (string) $r->nota : '';
                $this->observaciones[$r->trabajo_evaluable_id][$r->user_id] = $r->observaciones ?? '';
            }
        }

        // Alumnos agrupados por grupo
        $grupos = Grupo::with(['usuarios' => function ($q) {
                $q->where('rol', 'alumno')->orderBy('apellido');
            }, 'caso'])
            ->orderBy('nombre')
            ->get()
            ->filter(fn($g) => $g->usuarios->isNotEmpty());

        // Alumnos sin grupo
        $ids_con_grupo = $grupos->flatMap(fn($g) => $g->usuarios->pluck('id'));
        $sin_grupo = User::where('rol', 'alumno')
            ->whereNotIn('id', $ids_con_grupo)
            ->orderBy('apellido')
            ->get();

        return view('livewire.docente.gestion-notas', [
            'ciclos'    => $ciclos,
            'ciclo'     => $ciclo,
            'trabajos'  => $trabajos,
            'grupos'    => $grupos,
            'sin_grupo' => $sin_grupo,
        ]);
    }
}
