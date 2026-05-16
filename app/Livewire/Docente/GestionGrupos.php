<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\Grupo;
use App\Models\Caso;
use App\Models\User;

class GestionGrupos extends Component
{
    public string $nombre = '';
    public string $caso_id = '';
    public array $alumnos_seleccionados = [];
    public bool $mostrarModal = false;

    protected function rules(): array
    {
        return [
            'nombre'                => ['required', 'string', 'max:50'],
            'caso_id'               => ['required', 'exists:casos,id'],
            'alumnos_seleccionados' => ['required', 'array', 'min:1', 'max:2'],
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre.required'                => 'El nombre del grupo es obligatorio.',
            'caso_id.required'               => 'Debe seleccionar un caso.',
            'alumnos_seleccionados.required' => 'Debe seleccionar al menos un alumno.',
            'alumnos_seleccionados.max'      => 'Un grupo no puede tener más de 2 alumnos.',
        ];
    }

    public function abrirModal(): void
    {
        $this->reset(['nombre', 'caso_id', 'alumnos_seleccionados']);
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    public function crearGrupo(): void
    {
        $this->validate();

        $grupo = Grupo::create([
            'nombre'  => $this->nombre,
            'caso_id' => $this->caso_id,
            'estado'  => 'activo',
        ]);

        $grupo->usuarios()->attach($this->alumnos_seleccionados);

        $this->cerrarModal();
        session()->flash('mensaje', "Grupo {$grupo->nombre} creado correctamente.");
    }

    public function render()
    {
        $grupos = Grupo::with(['caso', 'usuarios'])
            ->orderBy('created_at', 'desc')
            ->get();

        $casos = Caso::where('activo', true)
            ->get();

        $alumnos_disponibles = User::where('rol', 'alumno')
            ->whereDoesntHave('grupos')
            ->orderBy('apellido')
            ->get();

        return view('livewire.docente.gestion-grupos', [
            'grupos'              => $grupos,
            'casos'               => $casos,
            'alumnos_disponibles' => $alumnos_disponibles,
        ]);
    }
}