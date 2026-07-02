<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\CasoFinal;
use App\Models\CasoFinalDocumento;
use App\Models\CasoFinalEntrevistado;

class GestionCasosFinales extends Component
{
    use WithFileUploads;

    // Lista
    public bool $soloActivos = true;

    // Formulario caso
    public ?int $editando_id = null;
    public string $nombre = '';
    public string $empresa = '';
    public string $antecedentes = '';
    public string $dificultad = 'media';
    public int $integrantes_min = 2;
    public int $integrantes_max = 4;
    public bool $mostrarFormulario = false;

    // Documento nuevo
    public ?int $doc_caso_id = null;
    public string $doc_titulo = '';
    public string $doc_descripcion = '';
    public $doc_archivo;

    // Entrevistado nuevo
    public ?int $ent_caso_id = null;
    public string $ent_nombre = '';
    public string $ent_cargo = '';
    public string $ent_area = '';
    public string $ent_descripcion = '';
    public $ent_archivo;

    // Vista detalle/impresión
    public ?int $viendo_id = null;

    public function nuevoCaso(): void
    {
        $this->reset(['editando_id','nombre','empresa','antecedentes','dificultad','integrantes_min','integrantes_max']);
        $this->integrantes_min = 2;
        $this->integrantes_max = 4;
        $this->dificultad = 'media';
        $this->mostrarFormulario = true;
    }

    public function editarCaso(int $id): void
    {
        $caso = CasoFinal::findOrFail($id);
        $this->editando_id     = $id;
        $this->nombre          = $caso->nombre;
        $this->empresa         = $caso->empresa;
        $this->antecedentes    = $caso->antecedentes ?? '';
        $this->dificultad      = $caso->dificultad;
        $this->integrantes_min = $caso->integrantes_min;
        $this->integrantes_max = $caso->integrantes_max;
        $this->mostrarFormulario = true;
    }

    public function guardarCaso(): void
    {
        $this->validate([
            'nombre'         => 'required|string|max:255',
            'empresa'        => 'required|string|max:255',
            'antecedentes'   => 'nullable|string',
            'dificultad'     => 'required|in:facil,media,dificil',
            'integrantes_min'=> 'required|integer|min:1|max:10',
            'integrantes_max'=> 'required|integer|min:1|max:10|gte:integrantes_min',
        ]);

        $data = [
            'nombre'          => $this->nombre,
            'empresa'         => $this->empresa,
            'antecedentes'    => $this->antecedentes,
            'dificultad'      => $this->dificultad,
            'integrantes_min' => $this->integrantes_min,
            'integrantes_max' => $this->integrantes_max,
        ];

        if ($this->editando_id) {
            CasoFinal::find($this->editando_id)->update($data);
        } else {
            CasoFinal::create(array_merge($data, ['activo' => true]));
        }

        $this->mostrarFormulario = false;
        session()->flash('mensaje', 'Caso guardado correctamente.');
    }

    public function toggleActivo(int $id): void
    {
        $caso = CasoFinal::find($id);
        $caso->update(['activo' => !$caso->activo]);
    }

    // Documentos
    public function abrirDocumento(int $caso_id): void
    {
        $this->doc_caso_id    = $caso_id;
        $this->doc_titulo     = '';
        $this->doc_descripcion = '';
        $this->doc_archivo    = null;
    }

    public function guardarDocumento(): void
    {
        $this->validate([
            'doc_titulo'    => 'required|string|max:255',
            'doc_descripcion' => 'nullable|string|max:255',
            'doc_archivo'   => 'nullable|file|mimes:pdf,doc,docx,vnd.openxmlformats-officedocument.wordprocessingml.document|max:20480',
        ]);

        $path = null;
        if ($this->doc_archivo) {
            $ext    = $this->doc_archivo->getClientOriginalExtension();
            $nombre = \Str::slug($this->doc_titulo . '-' . now()->format('YmdHis')) . '.' . $ext;
            $path   = $this->doc_archivo->storeAs('casos-finales', $nombre, 'uploads');
        }

        CasoFinalDocumento::create([
            'caso_final_id' => $this->doc_caso_id,
            'titulo'        => $this->doc_titulo,
            'descripcion'   => $this->doc_descripcion,
            'archivo_path'  => $path,
        ]);

        $this->doc_caso_id = null;
        session()->flash('mensaje', 'Documento agregado.');
    }

    public function eliminarDocumento(int $id): void
    {
        CasoFinalDocumento::find($id)?->delete();
    }

    // Entrevistados
    public function abrirEntrevistado(int $caso_id): void
    {
        $this->ent_caso_id     = $caso_id;
        $this->ent_nombre      = '';
        $this->ent_cargo       = '';
        $this->ent_area        = '';
        $this->ent_descripcion = '';
        $this->ent_archivo     = null;
    }

    public function guardarEntrevistado(): void
    {
        $this->validate([
            'ent_nombre'      => 'required|string|max:255',
            'ent_cargo'       => 'required|string|max:255',
            'ent_area'        => 'nullable|string|max:255',
            'ent_descripcion' => 'nullable|string',
            'ent_archivo'     => 'nullable|file|mimes:pdf,doc,docx,vnd.openxmlformats-officedocument.wordprocessingml.document|max:20480',
        ]);

        $path = null;
        if ($this->ent_archivo) {
            $ext    = $this->ent_archivo->getClientOriginalExtension();
            $nombre = \Str::slug($this->ent_nombre . '-' . now()->format('YmdHis')) . '.' . $ext;
            $path   = $this->ent_archivo->storeAs('casos-finales', $nombre, 'uploads');
        }

        CasoFinalEntrevistado::create([
            'caso_final_id'  => $this->ent_caso_id,
            'nombre'         => $this->ent_nombre,
            'cargo'          => $this->ent_cargo,
            'area'           => $this->ent_area,
            'descripcion_rol'=> $this->ent_descripcion,
            'archivo_path'   => $path,
        ]);

        $this->ent_caso_id = null;
        session()->flash('mensaje', 'Entrevistado agregado.');
    }

    public function eliminarEntrevistado(int $id): void
    {
        CasoFinalEntrevistado::find($id)?->delete();
    }

    public function verDetalle(int $id): void
    {
        $this->viendo_id = $id;
    }

    public function render()
    {
        $casos = CasoFinal::with(['documentos', 'entrevistados'])
            ->when($this->soloActivos, fn($q) => $q->where('activo', true))
            ->orderBy('nombre')
            ->get();

        $detalle = $this->viendo_id
            ? CasoFinal::with(['documentos', 'entrevistados'])->find($this->viendo_id)
            : null;

        return view('livewire.docente.gestion-casos-finales', [
            'casos'   => $casos,
            'detalle' => $detalle,
        ]);
    }
}
