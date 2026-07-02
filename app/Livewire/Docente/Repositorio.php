<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use App\Models\RepositorioArchivo;
use App\Models\Caso;

class Repositorio extends Component
{
    use WithFileUploads;

    public $archivo;
    public string $nombre_personalizado = '';
    public string $categoria            = 'documento';
    public string $caso_id              = '';
    public bool $mostrarModal           = false;
    public string $buscar               = '';
    public string $filtro_categoria     = '';
    public string $filtro_caso          = '';

    protected function rules(): array
    {
        return [
            'archivo'              => ['required', 'file', 'mimes:pdf,doc,docx,vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:20480'],
            'nombre_personalizado' => ['required', 'string', 'min:3', 'max:100'],
            'categoria'            => ['required', 'in:documento,entrevista,otro'],
            'caso_id'              => ['nullable'],
        ];
    }

    protected function messages(): array
    {
        return [
            'archivo.required'              => 'Debe seleccionar un archivo.',
            'archivo.mimes'                 => 'Solo se permiten PDF, DOC o DOCX.',
            'archivo.max'                   => 'El archivo no puede superar los 20MB.',
            'nombre_personalizado.required' => 'Debe ingresar un nombre para el archivo.',
        ];
    }

    public function abrirModal(): void
    {
        $this->reset(['archivo', 'nombre_personalizado', 'categoria', 'caso_id']);
        $this->categoria    = 'documento';
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    public function subirArchivo(): void
    {
        $this->validate();

        $extension      = $this->archivo->getClientOriginalExtension();
        $nombre         = \Str::slug($this->nombre_personalizado) . '.' . $extension;
        $nombre_original = $this->archivo->getClientOriginalName();
        $destino        = public_path('uploads' . DIRECTORY_SEPARATOR . 'repositorio');

        if (!File::exists($destino)) {
            File::makeDirectory($destino, 0755, true);
        }

        if (File::exists($destino . DIRECTORY_SEPARATOR . $nombre)) {
            $nombre = \Str::slug($this->nombre_personalizado) . '-' . now()->format('YmdHis') . '.' . $extension;
        }

        $this->archivo->storeAs('repositorio', $nombre, 'uploads');

        RepositorioArchivo::create([
            'nombre'          => $nombre,
            'nombre_original' => $nombre_original,
            'path'            => 'repositorio/' . $nombre,
            'categoria'       => $this->categoria,
            'caso_id'         => $this->caso_id ?: null,
        ]);

        $this->cerrarModal();
        session()->flash('mensaje', "Archivo {$nombre} subido correctamente.");
    }

    public function eliminarArchivo(int $id): void
    {
        $archivo = RepositorioArchivo::find($id);

        if ($archivo) {
            $path = public_path('uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $archivo->path));
            if (File::exists($path)) {
                File::delete($path);
            }
            $archivo->delete();
            session()->flash('mensaje', "Archivo eliminado correctamente.");
        }
    }

    public function render()
    {
        $casos = Caso::where('activo', true)->get();

        $archivos = RepositorioArchivo::with('caso')
            ->when($this->filtro_categoria, fn($q) => $q->where('categoria', $this->filtro_categoria))
            ->when($this->filtro_caso, fn($q) => $q->where('caso_id', $this->filtro_caso))
            ->when($this->buscar, fn($q) => $q->where('nombre', 'like', '%' . $this->buscar . '%'))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.docente.repositorio', [
            'archivos' => $archivos,
            'casos'    => $casos,
        ]);
    }
}