<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class GestionDocentes extends Component
{
    public bool $mostrarModal = false;
    public string $nombre    = '';
    public string $apellido  = '';
    public string $email     = '';
    public string $password  = '';

    public function abrirModal(): void
    {
        $this->reset(['nombre', 'apellido', 'email', 'password']);
        $this->mostrarModal = true;
    }

    public function guardar(): void
    {
        $this->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'nombre'   => $this->nombre,
            'apellido' => $this->apellido,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'rol'      => 'docente',
            'activo'   => true,
        ]);

        $this->mostrarModal = false;
        session()->flash('mensaje', 'Docente creado correctamente.');
    }

    public function eliminar(int $id): void
    {
        if ($id === auth()->id()) return;
        User::find($id)?->delete();
    }

    public function render()
    {
        return view('livewire.docente.gestion-docentes', [
            'docentes' => User::where('rol', 'docente')->orderBy('apellido')->get(),
        ]);
    }
}
