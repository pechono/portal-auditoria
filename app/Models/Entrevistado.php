<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrevistado extends Model
{
    protected $fillable = [
    'caso_id',
    'nombre',
    'cargo',
    'area',
    'descripcion_rol',
    'activo',
    'acta_path',  // ← agregá esto
];

    public function caso()
    {
        return $this->belongsTo(Caso::class);
    }
}