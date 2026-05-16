<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $fillable = [
    'caso_id',
    'codigo',
    'titulo',
    'descripcion',
    'archivo_path',
    'activo',
    'acceso_libre',  // ← verificá que esté
    ];

    protected $casts = [
        'acceso_libre' => 'boolean',
        'activo'       => 'boolean',
    ];

    public function caso()
    {
        return $this->belongsTo(Caso::class);
    }
}