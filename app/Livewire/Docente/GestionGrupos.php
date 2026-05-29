<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\Caso;
use App\Models\User;

class GestionGrupos extends Component
{
    public string $nombre              = '';
    public string $caso_id             = '';
    public array $alumnos_seleccionados = [];
    public bool $mostrarModal          = false;
    public ?int $editando_grupo        = null;

    protected function rules(): array
    {
        return [
            'nombre'                => ['required', 'string', 'max:50'],
            'caso_id'               => ['required', 'exists:casos,id'],
            'alumnos_seleccionados' => ['required', 'array', 'min:1', 'max:3'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre.required'                => 'El nombre del grupo es obligatorio.',
            'caso_id.required'               => 'Debe seleccionar un caso.',
            'alumnos_seleccionados.required' => 'Debe seleccionar al menos un alumno.',
            'alumnos_seleccionados.max'      => 'Un grupo no puede tener más de 3 alumnos.',
        ];
    }

    public function abrirModal(?int $grupo_id = null): void
    {
        $this->reset(['nombre', 'caso_id', 'alumnos_seleccionados', 'editando_grupo']);

        if ($grupo_id) {
            $grupo                       = Grupo::with('usuarios')->find($grupo_id);
            $this->nombre                = $grupo->nombre;
            $this->caso_id               = (string) $grupo->caso_id;
            $this->alumnos_seleccionados = $grupo->usuarios->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->editando_grupo        = $grupo_id;
        }

        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    public function guardarGrupo(): void
    {
        $this->validate();

        if ($this->editando_grupo) {
            $grupo = Grupo::find($this->editando_grupo);
            $grupo->update([
                'nombre'  => $this->nombre,
                'caso_id' => $this->caso_id,
            ]);
            // Sincroniza los alumnos (agrega los nuevos, quita los que no están)
            $grupo->usuarios()->sync($this->alumnos_seleccionados);
            session()->flash('mensaje', "Grupo {$grupo->nombre} actualizado correctamente.");
        } else {
            $grupo = Grupo::create([
                'nombre'  => $this->nombre,
                'caso_id' => $this->caso_id,
                'estado'  => 'activo',
            ]);
            $grupo->usuarios()->attach($this->alumnos_seleccionados);
            session()->flash('mensaje', "Grupo {$grupo->nombre} creado correctamente.");
        }

        $this->cerrarModal();
    }

    public function render()
    {
        $grupos = Grupo::with(['caso', 'usuarios'])
            ->orderBy('created_at', 'desc')
            ->get();

        $casos = Caso::where('activo', true)->get();

        // Al editar mostramos todos los alumnos disponibles MAS los del grupo actual
        $alumnos_del_grupo = $this->editando_grupo
            ? Grupo::find($this->editando_grupo)?->usuarios->pluck('id')->toArray()
            : [];

        $alumnos_disponibles = User::where('rol', 'alumno')
            ->where(function ($q) use ($alumnos_del_grupo) {
                $q->whereDoesntHave('grupos')
                  ->orWhereIn('id', $alumnos_del_grupo);
            })
            ->orderBy('apellido')
            ->get();

        return view('livewire.docente.gestion-grupos', [
            'grupos'              => $grupos,
            'casos'               => $casos,
            'alumnos_disponibles' => $alumnos_disponibles,
        ]);
    }
}