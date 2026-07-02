<?php

namespace App\Livewire\Docente;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Solicitud;
use App\Models\Documento;
use App\Models\Grupo;

class GestionSolicitudes extends Component
{
    use WithFileUploads, WithPagination;

    public bool $mostrarModal    = false;
    public int $solicitud_id     = 0;
    public string $comentario    = '';
    public string $accion        = '';
    public string $recurso_id    = '';
    public string $filtro_estado = 'pendiente';
    public string $filtro_tipo   = '';
    public string $busqueda      = '';
    public string $vista         = 'grupos';
    public $acta;

    protected $paginationTheme = 'tailwind';

    public function updatingFiltroEstado(): void { $this->resetPage(); }
    public function updatingFiltroTipo(): void   { $this->resetPage(); }
    public function updatingBusqueda(): void     { $this->resetPage(); }

    public function cambiarVista(string $v): void
    {
        $this->vista = $v;
        $this->resetPage();
    }

    public function limpiarFiltros(): void
    {
        $this->filtro_tipo = '';
        $this->busqueda    = '';
        $this->resetPage();
    }

    public function abrirModal(int $solicitud_id, string $accion): void
    {
        $this->reset(['comentario', 'recurso_id', 'acta']);
        $this->solicitud_id = $solicitud_id;
        $this->accion       = $accion;
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }
    public function updatedActa(): void
    {
    $this->validate([
            'acta' => ['nullable', 'file', 'mimes:pdf,doc,docx,vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:10240'],
        ]);
    } 
    public function procesarSolicitud(): void
    {
        $solicitud = Solicitud::with('grupo')->find($this->solicitud_id);

        // Caso especial: solo subir acta
        if ($this->accion === 'subir_acta') {
            $this->validate([
                'acta' => ['required', 'file', 'mimes:pdf,doc,docx,vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:10240'],
            ]);
            $extension = $this->acta->getClientOriginalExtension();
            $nombre    = \Str::slug('acta-' . $solicitud->grupo->nombre . '-' . now()->format('YmdHis')) . '.' . $extension;
            $acta_path = $this->acta->storeAs('actas', $nombre, 'uploads');
            $solicitud->update(['acta_path' => $acta_path]);
            $this->cerrarModal();
            session()->flash('mensaje', 'Acta subida correctamente.');
            return;
        }

        // Validar según tipo y acción
        if ($this->accion === 'aprobar' && $solicitud->tipo === 'documento') {
            $this->validate([
                'recurso_id' => ['required', 'integer', 'min:1'],
                'comentario' => ['nullable', 'string', 'max:500'],
                'acta'       => ['nullable', 'file', 'mimes:pdf,doc,docx,vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:10240'],
            ]);
        } elseif ($this->accion === 'aprobar' && $solicitud->tipo === 'entrevista') {
            $this->validate([
                'recurso_id' => ['required', 'integer', 'min:1'],
                'comentario' => ['nullable', 'string', 'max:500'],
                'acta'       => ['nullable', 'file', 'mimes:pdf,doc,docx,vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:10240'],
            ]);
        } else {
            $this->validate([
                'comentario' => ['nullable', 'string', 'max:500'],
            ]);
        }

        // $acta_path = null;

        // if ($this->accion === 'aprobar' && $solicitud->tipo === 'entrevista' && $this->acta) {
        //     $extension = $this->acta->getClientOriginalExtension();
        //     $nombre    = \Str::slug('acta-' . $solicitud->grupo->nombre . '-' . now()->format('YmdHis')) . '.' . $extension;
        //     $acta_path = $this->acta->storeAs('actas', $nombre, 'uploads');
        // }
        // $acta_path = null;

        if ($this->accion === 'aprobar' && $solicitud->tipo === 'entrevista' && $this->recurso_id) {
            // Buscar el acta del entrevistado asignado
            $entrevistado = \App\Models\Entrevistado::find($this->recurso_id);
            $acta_path    = $entrevistado?->acta_path;
        }

        $solicitud->update([
            'estado'             => $this->accion === 'aprobar' ? 'aprobada' : 'rechazada',
            'comentario_docente' => $this->comentario,
            'revisado_por'       => auth()->id(),
            'revisado_at'        => now(),
            'recurso_id'         => $this->accion === 'aprobar' && $this->recurso_id
                                        ? $this->recurso_id
                                        : $solicitud->recurso_id,
            'acta_path'          => $acta_path ?? $solicitud->acta_path,
        ]);

        // Notificar al alumno
        $grupo = $solicitud->grupo;
        foreach ($grupo->usuarios as $alumno) {
            \App\Models\Notificacion::create([
                'user_id' => $alumno->id,
                'tipo'    => 'solicitud_' . $solicitud->estado,
                'mensaje' => $this->accion === 'aprobar'
                    ? "Tu solicitud de {$solicitud->tipo} fue aprobada. {$this->comentario}"
                    : "Tu solicitud de {$solicitud->tipo} fue rechazada. {$this->comentario}",
                'leida'   => false,
            ]);
        }


        $this->cerrarModal();
        session()->flash('mensaje', 'Solicitud ' . ($this->accion === 'aprobar' ? 'aprobada' : 'rechazada') . ' correctamente.');
    }

    public function render()
    {
        // grupo_id => colección de recurso_ids ya entregados
        $docs_entregados_por_grupo = Solicitud::where('estado', 'aprobada')
            ->where('tipo', 'documento')
            ->whereNotNull('recurso_id')
            ->get()
            ->groupBy('grupo_id')
            ->map(fn($sols) => $sols->pluck('recurso_id'));

        $documentos    = Documento::where('activo', true)->get();
        $entrevistados = \App\Models\Entrevistado::where('activo', true)->get();

        $repositorio_path = public_path('uploads' . DIRECTORY_SEPARATOR . 'repositorio');
        $archivos = [];
        if (\Illuminate\Support\Facades\File::exists($repositorio_path)) {
            $archivos = collect(\Illuminate\Support\Facades\File::files($repositorio_path))
                ->map(fn($file) => $file->getFilename())
                ->sort()
                ->values()
                ->toArray();
        }

        $busqueda = trim($this->busqueda);

        if ($this->vista === 'grupos') {
            $grupos = Grupo::with(['caso', 'usuarios',
                    'solicitudes' => fn($q) => $q->with('solicitante')
                        ->where('estado', $this->filtro_estado)
                        ->when($this->filtro_tipo, fn($q) => $q->where('tipo', $this->filtro_tipo))
                        ->orderBy('created_at', 'desc'),
                ])
                ->whereHas('solicitudes', function ($q) {
                    $q->where('estado', $this->filtro_estado);
                    if ($this->filtro_tipo) {
                        $q->where('tipo', $this->filtro_tipo);
                    }
                })
                ->when($busqueda, fn($q) => $q->where('nombre', 'like', "%{$busqueda}%"))
                ->orderBy('nombre')
                ->paginate(5);

            return view('livewire.docente.gestion-solicitudes', [
                'grupos'                    => $grupos,
                'solicitudes'               => collect(),
                'documentos'                => $documentos,
                'entrevistados'             => $entrevistados,
                'archivos'                  => $archivos,
                'docs_entregados_por_grupo' => $docs_entregados_por_grupo,
            ]);
        }

        // Vista lista con paginación
        $solicitudes = Solicitud::with(['grupo.caso', 'solicitante'])
            ->where('estado', $this->filtro_estado)
            ->when($this->filtro_tipo, fn($q) => $q->where('tipo', $this->filtro_tipo))
            ->when($busqueda, fn($q) => $q->whereHas('grupo', fn($q) => $q->where('nombre', 'like', "%{$busqueda}%")))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.docente.gestion-solicitudes', [
            'grupos'                    => collect(),
            'solicitudes'               => $solicitudes,
            'documentos'                => $documentos,
            'entrevistados'             => $entrevistados,
            'archivos'                  => $archivos,
            'docs_entregados_por_grupo' => $docs_entregados_por_grupo,
        ]);
    }
}