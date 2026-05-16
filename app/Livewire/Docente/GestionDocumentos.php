<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\Documento;
use App\Models\Caso;
use Illuminate\Support\Facades\File;

class GestionDocumentos extends Component
{
    public bool $mostrarModal    = false;
    public int $documento_id     = 0;
    public string $filtro_caso   = '';
    public string $archivo_elegido = '';

    protected function rules(): array
    {
        return [
            'archivo_elegido' => ['required', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'archivo_elegido.required' => 'Debe seleccionar un archivo del repositorio.',
        ];
    }

    public function abrirModal(int $documento_id): void
    {
        $this->reset(['archivo_elegido']);
        $this->documento_id = $documento_id;
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    public function asignarArchivo(): void
    {
        $this->validate();

        $documento = Documento::find($this->documento_id);

        $documento->update([
            'archivo_path' => 'repositorio/' . $this->archivo_elegido,
        ]);

        $this->cerrarModal();
        session()->flash('mensaje', "Archivo asignado correctamente a {$documento->codigo} — {$documento->titulo}.");
    }

    public function render()
    {
        $casos = Caso::where('activo', true)->get();

        $documentos = Documento::with('caso')
            ->when($this->filtro_caso, function ($query) {
                $query->where('caso_id', $this->filtro_caso);
            })
            ->orderBy('caso_id')
            ->orderBy('codigo')
            ->get();

        // Listar archivos del repositorio
        $repositorio_path = public_path('uploads/repositorio');
        $archivos = [];

        if (File::exists($repositorio_path)) {
            $archivos = collect(File::files($repositorio_path))
                ->map(fn($file) => $file->getFilename())
                ->sort()
                ->values()
                ->toArray();
        }

        return view('livewire.docente.gestion-documentos', [
            'casos'      => $casos,
            'documentos' => $documentos,
            'archivos'   => $archivos,
        ]);
    }
}