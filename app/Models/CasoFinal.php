<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasoFinal extends Model
{
    protected $table = 'casos_finales';

    protected $fillable = [
        'nombre', 'empresa', 'antecedentes',
        'dificultad', 'integrantes_min', 'integrantes_max',
        'resultado_esperado', 'activo',
    ];

    public function documentos()
    {
        return $this->hasMany(CasoFinalDocumento::class);
    }

    public function entrevistados()
    {
        return $this->hasMany(CasoFinalEntrevistado::class);
    }

    public function getDificultadLabelAttribute(): string
    {
        return match($this->dificultad) {
            'facil'   => 'Fácil',
            'media'   => 'Media',
            'dificil' => 'Difícil',
            default   => $this->dificultad,
        };
    }
}
