<?php

namespace App\Livewire\Alumno;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Documento;

class SolicitudRecursos extends Component
{
    // Modal documentos
    public bool $mostrarModalDocumento  = false;
    public string $justificacion_doc    = '';
    public string $documento_solicitado = '';

    // Modal entrevistas
    public bool $mostrarModalEntrevista  = false;
    public string $justificacion_ent     = '';
    public string $persona_solicitada    = '';

    protected function rules(): array
    {
        return [
            'justificacion_doc'    => ['required', 'string', 'min:30'],
            'documento_solicitado' => ['required', 'string', 'min:3'],
            'justificacion_ent'    => ['required', 'string', 'min:30'],
            'persona_solicitada'   => ['required', 'string', 'min:3'],
        ];
    }
    




    protected function messages(): array
    {
        return [
            'justificacion_doc.required'  => 'La justificación es obligatoria.',
            'justificacion_doc.min'       => 'La justificación debe tener al menos 30 caracteres.',
            'documento_solicitado.required' => 'Debe indicar qué documento necesita.',
            'justificacion_ent.required'  => 'La justificación es obligatoria.',
            'justificacion_ent.min'       => 'La justificación debe tener al menos 30 caracteres.',
            'persona_solicitada.required' => 'Debe indicar con quién querés hablar.',
        ];
    }

    // ── Documentos ──────────────────────────────
    public function abrirModalDocumento(): void
    {
        $this->reset(['justificacion_doc', 'documento_solicitado']);
        $this->mostrarModalDocumento = true;
    }

    public function cerrarModalDocumento(): void
    {
        $this->mostrarModalDocumento = false;
    }

    public function enviarSolicitudDocumento(): void
    {
        $this->validateOnly('justificacion_doc');
        $this->validateOnly('documento_solicitado');

        $grupo = auth()->user()->grupos()->first();

        Solicitud::create([
            'grupo_id'       => $grupo->id,
            'solicitante_id' => auth()->id(),
            'tipo'           => 'documento',
            'recurso_id'     => 0,
            'justificacion'  => "Documento solicitado: {$this->documento_solicitado}\n\nJustificación: {$this->justificacion_doc}",
            'estado'         => 'pendiente',
        ]);

        $this->cerrarModalDocumento();
        session()->flash('mensaje', 'Solicitud de documento enviada. El docente la revisará a la brevedad.');
    }

    // ── Entrevistas ──────────────────────────────
    public function abrirModalEntrevista(): void
    {
        $this->reset(['justificacion_ent', 'persona_solicitada']);
        $this->mostrarModalEntrevista = true;
    }

    public function cerrarModalEntrevista(): void
    {
        $this->mostrarModalEntrevista = false;
    }

    public function enviarSolicitudEntrevista(): void
    {
        $this->validateOnly('justificacion_ent');
        $this->validateOnly('persona_solicitada');

        $grupo = auth()->user()->grupos()->first();

        Solicitud::create([
            'grupo_id'       => $grupo->id,
            'solicitante_id' => auth()->id(),
            'tipo'           => 'entrevista',
            'recurso_id'     => 0,
            'justificacion'  => "Persona solicitada: {$this->persona_solicitada}\n\nJustificación: {$this->justificacion_ent}",
            'estado'         => 'pendiente',
        ]);

        $this->cerrarModalEntrevista();
        session()->flash('mensaje', 'Solicitud de entrevista enviada. El docente la revisará a la brevedad.');
    }

    public function render()
    {
        $grupo = auth()->user()->grupos()->with('caso')->first();

        if (!$grupo) {
            return view('livewire.alumno.solicitud-recursos', [
                'grupo'            => null,
                'docs_libres'      => collect(),
                'solicitudes_docs' => collect(),
                'solicitudes_ents' => collect(),
            ]);
        }

        $docs_libres = Documento::where('caso_id', $grupo->caso_id)
            ->where('acceso_libre', true)
            ->where('activo', true)
            ->get();

        $solicitudes_docs = Solicitud::where('grupo_id', $grupo->id)
            ->where('tipo', 'documento')
            ->orderBy('created_at', 'desc')
            ->get();

        

        $solicitudes_ents = Solicitud::where('grupo_id', $grupo->id)
            ->where('tipo', 'entrevista')
            ->orderBy('created_at', 'desc')
             ->get();

        return view('livewire.alumno.solicitud-recursos', [
            'grupo'            => $grupo,
            'docs_libres'      => $docs_libres,
            'solicitudes_docs' => $solicitudes_docs,
            'solicitudes_ents' => $solicitudes_ents,
        ]);
    }
}