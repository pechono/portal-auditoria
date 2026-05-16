<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
     protected $table = 'solicitudes'; // ← agregá esto
    protected $fillable = [
        'grupo_id',
        'solicitante_id',
        'tipo',
        'recurso_id',
        'justificacion',
        'estado',
        'comentario_docente',
        'revisado_por',
        'revisado_at',
    ];

    protected $casts = [
        'revisado_at' => 'datetime',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    public function recurso()
    {
        if ($this->tipo === 'documento') {
            return $this->belongsTo(Documento::class, 'recurso_id');
        }
        return $this->belongsTo(Entrevistado::class, 'recurso_id');
    }
}