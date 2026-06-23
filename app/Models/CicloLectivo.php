<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicloLectivo extends Model
{
    protected $table = 'ciclos_lectivos';

    protected $fillable = ['nombre', 'anio', 'observaciones', 'activo'];

    public function trabajos()
    {
        return $this->hasMany(TrabajoEvaluable::class)->orderBy('orden');
    }

    public function notas()
    {
        return $this->hasMany(NotaAlumno::class);
    }
}
