<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CasoFinalDocumento extends Model
{
    protected $table = 'caso_final_documentos';

    protected $fillable = ['caso_final_id', 'titulo', 'descripcion', 'archivo_path'];

    public function casoFinal()
    {
        return $this->belongsTo(CasoFinal::class);
    }
}
