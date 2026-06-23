<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaAlumno extends Model
{
    protected $table = 'notas_alumnos';

    protected $fillable = [
        'ciclo_lectivo_id', 'user_id', 'trabajo_evaluable_id',
        'grupo_id', 'caso_id', 'nota', 'observaciones',
    ];

    public function alumno()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trabajo()
    {
        return $this->belongsTo(TrabajoEvaluable::class, 'trabajo_evaluable_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function caso()
    {
        return $this->belongsTo(Caso::class);
    }
}
