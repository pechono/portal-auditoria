<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use App\Models\Caso;
use App\Models\Documento;
use App\Models\Entrevistado;
use App\Models\Etapa;
use App\Models\RepositorioArchivo;

class GestionCasos extends Component
{
    public string $panel   = 'casos';
    public ?int $caso_id   = null;

    // Form caso
    public bool $mostrarModalCaso = false;
    public string $codigo         = '';
    public string $nombre         = '';
    public string $descripcion    = '';
    public bool $activo           = true;
    public int $dificultad        = 1;
    public string $tipo           = 'grupal';
    public ?int $editando_caso    = null;

    // Form documento
    public bool $mostrarModalDoc   = false;
    public string $doc_codigo      = '';
    public string $doc_titulo      = '';
    public string $doc_descripcion = '';
    public $doc_acceso_libre       = false;
    public string $doc_archivo     = '';
    public ?int $editando_doc      = null;

    // Form entrevistado
    public bool $mostrarModalEnt   = false;
    public string $ent_nombre      = '';
    public string $ent_cargo       = '';
    public string $ent_area        = '';
    public string $ent_descripcion = '';
    public string $ent_acta        = '';
    public ?int $editando_ent      = null;

    // ── Casos ──────────────────────────────────────
    public function abrirModalCaso(?int $id = null): void
    {
        $this->reset(['codigo', 'nombre', 'descripcion', 'activo', 'dificultad', 'tipo', 'editando_caso']);
        $this->activo     = true;
        $this->dificultad = 1;
        $this->tipo       = 'grupal';

        if ($id) {
            $caso                = Caso::find($id);
            $this->codigo        = $caso->codigo;
            $this->nombre        = $caso->nombre;
            $this->descripcion   = $caso->descripcion ?? '';
            $this->activo        = $caso->activo;
            $this->dificultad    = $caso->dificultad ?? 1;
            $this->tipo          = $caso->tipo ?? 'grupal';
            $this->editando_caso = $id;
        }

        $this->mostrarModalCaso = true;
    }

    public function cerrarModalCaso(): void
    {
        $this->mostrarModalCaso = false;
    }

    public function guardarCaso(): void
    {
        $this->validate([
            'codigo'      => ['required', 'string', 'max:20'],
            'nombre'      => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'dificultad'  => ['required', 'integer', 'min:1', 'max:5'],
            'tipo'        => ['required', 'in:grupal,individual'],
        ]);

        if ($this->editando_caso) {
            Caso::find($this->editando_caso)->update([
                'codigo'      => strtoupper($this->codigo),
                'nombre'      => $this->nombre,
                'descripcion' => $this->descripcion,
                'activo'      => $this->activo,
                'dificultad'  => $this->dificultad,
                'tipo'        => $this->tipo,
            ]);
            session()->flash('mensaje', 'Caso actualizado correctamente.');
        } else {
            $caso = Caso::create([
                'codigo'      => strtoupper($this->codigo),
                'nombre'      => $this->nombre,
                'descripcion' => $this->descripcion,
                'activo'      => $this->activo,
                'dificultad'  => $this->dificultad,
                'tipo'        => $this->tipo,
            ]);

            $etapas = [
                ['numero' => 1, 'nombre' => 'Solicitud inicial y conformación de grupo', 'descripcion' => 'El grupo se registra y presenta la solicitud inicial de auditoría.', 'orden' => 1],
                ['numero' => 2, 'nombre' => 'Plan de auditoría',                         'descripcion' => 'Elaboración y presentación del plan de auditoría para aprobación docente.', 'orden' => 2],
                ['numero' => 3, 'nombre' => 'Recolección de evidencia',                  'descripcion' => 'Solicitud y análisis de documentos y entrevistas.', 'orden' => 3],
                ['numero' => 4, 'nombre' => 'Informe final',                             'descripcion' => 'Redacción y entrega del informe final de auditoría.', 'orden' => 4],
                ['numero' => 5, 'nombre' => 'Defensa oral',                              'descripcion' => 'Presentación y defensa oral ante el docente.', 'orden' => 5],
            ];

            foreach ($etapas as $etapa) {
                Etapa::create(array_merge($etapa, ['caso_id' => $caso->id]));
            }

            session()->flash('mensaje', 'Caso creado correctamente con sus 5 etapas.');
        }

        $this->cerrarModalCaso();
    }

    public function seleccionarCaso(int $id, string $panel): void
    {
        $this->caso_id = $id;
        $this->panel   = $panel;
    }

    // ── Documentos ──────────────────────────────────────
    public function abrirModalDoc(?int $id = null): void
    {
        $this->reset(['doc_codigo', 'doc_titulo', 'doc_descripcion', 'doc_acceso_libre', 'doc_archivo', 'editando_doc']);

        if ($id) {
            $doc                    = Documento::find($id);
            $this->doc_codigo       = $doc->codigo;
            $this->doc_titulo       = $doc->titulo;
            $this->doc_descripcion  = $doc->descripcion ?? '';
            $this->doc_acceso_libre = $doc->acceso_libre;
            $this->editando_doc     = $id;

            // Buscar el archivo en el repositorio por path
            if ($doc->archivo_path) {
                $arch = RepositorioArchivo::where('path', $doc->archivo_path)->first();
                $this->doc_archivo = $arch ? (string) $arch->id : '';
            }
        }

        $this->mostrarModalDoc = true;
    }

    public function cerrarModalDoc(): void
    {
        $this->mostrarModalDoc = false;
    }

    public function archivoSeleccionadoDoc(): void
    {
        if ($this->doc_archivo && empty($this->doc_titulo)) {
            $archivo = RepositorioArchivo::find($this->doc_archivo);
            if ($archivo) {
                $this->doc_titulo = $archivo->nombre;
            }
        }
    }

    public function guardarDocumento(): void
    {
        $this->validate([
            'doc_codigo'      => ['required', 'string', 'max:20'],
            'doc_titulo'      => ['required', 'string', 'max:255'],
            'doc_descripcion' => ['nullable', 'string'],
        ]);

        $archivo_path = null;
        if ($this->doc_archivo) {
            $archivo = RepositorioArchivo::find($this->doc_archivo);
            $archivo_path = $archivo?->path;
        }

        $datos = [
            'caso_id'      => $this->caso_id,
            'codigo'       => strtoupper($this->doc_codigo),
            'titulo'       => $this->doc_titulo,
            'descripcion'  => $this->doc_descripcion,
            'acceso_libre' => (bool) $this->doc_acceso_libre,
            'archivo_path' => $archivo_path,
            'activo'       => true,
        ];

        if ($this->editando_doc) {
            Documento::find($this->editando_doc)->update($datos);
            session()->flash('mensaje', 'Documento actualizado correctamente.');
        } else {
            Documento::create($datos);
            session()->flash('mensaje', 'Documento agregado correctamente.');
        }

        $this->cerrarModalDoc();
    }

    public function eliminarDocumento(int $id): void
    {
        Documento::find($id)->delete();
        session()->flash('mensaje', 'Documento eliminado.');
    }

    // ── Entrevistados ──────────────────────────────────────
    public function abrirModalEnt(?int $id = null): void
    {
        $this->reset(['ent_nombre', 'ent_cargo', 'ent_area', 'ent_descripcion', 'ent_acta', 'editando_ent']);

        if ($id) {
            $ent                   = Entrevistado::find($id);
            $this->ent_nombre      = $ent->nombre;
            $this->ent_cargo       = $ent->cargo;
            $this->ent_area        = $ent->area;
            $this->ent_descripcion = $ent->descripcion_rol ?? '';
            $this->editando_ent    = $id;

            if ($ent->acta_path) {
                $arch = RepositorioArchivo::where('path', $ent->acta_path)->first();
                $this->ent_acta = $arch ? (string) $arch->id : '';
            }
        }

        $this->mostrarModalEnt = true;
    }

    public function cerrarModalEnt(): void
    {
        $this->mostrarModalEnt = false;
    }

    public function guardarEntrevistado(): void
    {
        $this->validate([
            'ent_nombre'      => ['required', 'string', 'max:255'],
            'ent_cargo'       => ['required', 'string', 'max:255'],
            'ent_area'        => ['required', 'string', 'max:255'],
            'ent_descripcion' => ['nullable', 'string'],
        ]);

        $acta_path = null;
        if ($this->ent_acta) {
            $archivo   = RepositorioArchivo::find($this->ent_acta);
            $acta_path = $archivo?->path;
        }

        $datos = [
            'caso_id'         => $this->caso_id,
            'nombre'          => $this->ent_nombre,
            'cargo'           => $this->ent_cargo,
            'area'            => $this->ent_area,
            'descripcion_rol' => $this->ent_descripcion,
            'acta_path'       => $acta_path,
            'activo'          => true,
        ];

        if ($this->editando_ent) {
            Entrevistado::find($this->editando_ent)->update($datos);
            session()->flash('mensaje', 'Entrevistado actualizado correctamente.');
        } else {
            Entrevistado::create($datos);
            session()->flash('mensaje', 'Entrevistado agregado correctamente.');
        }

        $this->cerrarModalEnt();
    }

    public function eliminarEntrevistado(int $id): void
    {
        Entrevistado::find($id)->delete();
        session()->flash('mensaje', 'Entrevistado eliminado.');
    }

    public function render()
    {
        $casos             = Caso::orderBy('created_at', 'desc')->get();
        $caso_seleccionado = $this->caso_id ? Caso::find($this->caso_id) : null;

        $documentos = $this->caso_id
            ? Documento::where('caso_id', $this->caso_id)->orderBy('codigo')->get()
            : collect();

        $entrevistados = $this->caso_id
            ? Entrevistado::where('caso_id', $this->caso_id)->orderBy('nombre')->get()
            : collect();

        $archivos_docs = $this->caso_id
            ? RepositorioArchivo::where('caso_id', $this->caso_id)
                ->where('categoria', 'documento')
                ->orderBy('nombre')
                ->get()
            : collect();

        $archivos_ents = $this->caso_id
            ? RepositorioArchivo::where('caso_id', $this->caso_id)
                ->where('categoria', 'entrevista')
                ->orderBy('nombre')
                ->get()
            : collect();

        return view('livewire.docente.gestion-casos', [
            'casos'             => $casos,
            'caso_seleccionado' => $caso_seleccionado,
            'documentos'        => $documentos,
            'entrevistados'     => $entrevistados,
            'archivos_docs'     => $archivos_docs,
            'archivos_ents'     => $archivos_ents,
        ]);
    }
}