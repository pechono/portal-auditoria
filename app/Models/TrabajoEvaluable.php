<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrabajoEvaluable extends Model
{
    protected $fillable = ['ciclo_lectivo_id', 'nombre', 'orden'];

    public function ciclo()
    {
        return $this->belongsTo(CicloLectivo::class, 'ciclo_lectivo_id');
    }

    public function notas()
    {
        return $this->hasMany(NotaAlumno::class);
    }
}
