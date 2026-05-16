<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas';
    protected $fillable = [
        'grupo_id',
        'etapa_id',
        'archivo_path',
        'archivo_nombre',
        'estado',
        'comentario_docente',
        'devolucion_path',
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

    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
}