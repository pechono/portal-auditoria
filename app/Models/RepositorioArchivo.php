<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepositorioArchivo extends Model
{
    protected $table = 'repositorio_archivos';

    protected $fillable = [
        'nombre',
        'nombre_original',
        'path',
        'categoria',
        'caso_id',
    ];

    public function caso()
    {
        return $this->belongsTo(Caso::class);
    }
}
