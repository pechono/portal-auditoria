<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GestionAlumnos extends Component
{
    public bool $mostrarModal    = false;
    public string $nombre        = '';
    public string $apellido      = '';
    public string $email         = '';
    public ?int $editando_alumno = null;
    public string $buscar        = '';

    protected function rules(): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . ($this->editando_alumno ?? 'NULL')],
        ];
    }

    protected function messages(): array
    {
        return [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'email.required'    => 'El email es obligatorio.',
            'email.unique'      => 'Ya existe un alumno con ese email.',
        ];
    }

    public function abrirModal(?int $id = null): void
    {
        $this->reset(['nombre', 'apellido', 'email', 'editando_alumno']);

        if ($id) {
            $alumno              = User::find($id);
            $this->nombre        = $alumno->nombre;
            $this->apellido      = $alumno->apellido;
            $this->email         = $alumno->email;
            $this->editando_alumno = $id;
        }

        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    public function guardarAlumno(): void
    {
        $this->validate();

        if ($this->editando_alumno) {
            User::find($this->editando_alumno)->update([
                'nombre'   => $this->nombre,
                'apellido' => $this->apellido,
                'email'    => $this->email,
            ]);
            session()->flash('mensaje', 'Alumno actualizado correctamente.');
        } else {
            // Generar contraseña temporal
            $password_temporal = Str::random(8);

            User::create([
                'nombre'   => $this->nombre,
                'apellido' => $this->apellido,
                'email'    => $this->email,
                'password' => Hash::make($password_temporal),
                'rol'      => 'alumno',
                'activo'   => true,
            ]);

            session()->flash('mensaje', "Alumno creado. Contraseña temporal: {$password_temporal} — Entregásela al alumno.");
        }

        $this->cerrarModal();
    }

    public function resetearPassword(int $id): void
    {
        $password_temporal = Str::random(8);
        User::find($id)->update([
            'password' => Hash::make($password_temporal),
        ]);
        session()->flash('mensaje', "Contraseña reseteada. Nueva contraseña temporal: {$password_temporal}");
    }

    public function render()
    {
        $alumnos = User::where('rol', 'alumno')
            ->when($this->buscar, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->buscar . '%')
                      ->orWhere('apellido', 'like', '%' . $this->buscar . '%')
                      ->orWhere('email', 'like', '%' . $this->buscar . '%');
                });
            })
            ->orderBy('apellido')
            ->get();

        return view('livewire.docente.gestion-alumnos', [
            'alumnos' => $alumnos,
        ]);
    }
}