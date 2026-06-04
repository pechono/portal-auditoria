<?php

namespace App\Livewire\Alumno;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Entrega;
use App\Models\Etapa;

class EntregaEtapa extends Component
{
    use WithFileUploads;

    public $archivo;
    public bool $mostrarModal = false;
    public int $etapa_id = 0;

    protected function rules(): array
    {
        return [
            'archivo'  => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'etapa_id' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function messages(): array
    {
        return [
            'archivo.required' => 'Debe seleccionar un archivo.',
            'archivo.mimes'    => 'Solo se permiten archivos PDF, DOC o DOCX.',
            'archivo.max'      => 'El archivo no puede superar los 10MB.',
        ];
    }

    public function abrirModal(int $etapa_id): void
    {
        $this->reset(['archivo']);
        $this->etapa_id     = $etapa_id;
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
    }

    public function subirEntrega(): void
    {
        $this->validate();

        $grupo = auth()->user()->grupos()->first();

        $entregaAprobada = Entrega::where('grupo_id', $grupo->id)
            ->where('etapa_id', $this->etapa_id)
            ->where('estado', 'aprobada')
            ->exists();

        if ($entregaAprobada) {
            $this->addError('archivo', 'Esta etapa ya fue aprobada.');
            return;
        }

        
        $extension       = $this->archivo->getClientOriginalExtension();
        $nombre_original = pathinfo($this->archivo->getClientOriginalName(), PATHINFO_FILENAME);
        $nombre          = \Str::slug($nombre_original) . '-' . now()->format('Y-m-d-His') . '.' . $extension;
        $path            = $this->archivo->storeAs('entregas', $nombre, 'uploads');


        Entrega::create([
            'grupo_id'       => $grupo->id,
            'etapa_id'       => $this->etapa_id,
            'archivo_path'   => $path,
            'archivo_nombre' => $nombre,  // ← nombre legible
            'estado'         => 'enviada',
        ]);



        $this->cerrarModal();
        session()->flash('mensaje_entrega', 'Entrega enviada correctamente. El docente la revisará a la brevedad.');
    }

    public function render()
    {
        $grupo = auth()->user()->grupos()->with('caso')->first();

        if (!$grupo) {
            return view('livewire.alumno.entrega-etapa', [
                'grupo'            => null,
                'etapas'           => collect(),
                'entregas'         => collect(),
                'plan_aprobado'    => false,
                'etapa_3_aprobada' => false,
            ]);
        }

        $etapas = Etapa::where('caso_id', $grupo->caso_id)
            ->orderBy('orden')
            ->get();

        $entregas = Entrega::where('grupo_id', $grupo->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('etapa_id');

        // Verificar si el plan está aprobado (etapa 2)
        $etapa_plan    = $etapas->firstWhere('numero', 2);
        $plan_aprobado = false;

        if ($etapa_plan) {
            $plan_aprobado = Entrega::where('grupo_id', $grupo->id)
                ->where('etapa_id', $etapa_plan->id)
                ->where('estado', 'aprobada')
                ->exists();
        }

        // Verificar si etapa 3 está aprobada
        $etapa_3          = $etapas->firstWhere('numero', 3);
        $etapa_3_aprobada = false;

        if ($etapa_3) {
            $etapa_3_aprobada = Entrega::where('grupo_id', $grupo->id)
                ->where('etapa_id', $etapa_3->id)
                ->where('estado', 'aprobada')
                ->exists();
        }

        // Marcar notificaciones de entrega como leídas al entrar
        \App\Models\Notificacion::where('user_id', auth()->id())
            ->where('tipo', 'like', 'entrega_%')
            ->where('leida', false)
            ->update(['leida' => true]);

        return view('livewire.alumno.entrega-etapa', [
            'grupo'            => $grupo,
            'etapas'           => $etapas,
            'entregas'         => $entregas,
            'plan_aprobado'    => $plan_aprobado,
            'etapa_3_aprobada' => $etapa_3_aprobada,
        ]);
    }
}