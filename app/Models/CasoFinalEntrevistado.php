<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasoFinalEntrevistado extends Model
{
    protected $table = 'caso_final_entrevistados';

    protected $fillable = ['caso_final_id', 'nombre', 'cargo', 'area', 'descripcion_rol'];

    public function casoFinal()
    {
        return $this->belongsTo(CasoFinal::class);
    }
}
