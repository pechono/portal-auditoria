<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    protected $fillable = ['codigo', 'nombre', 'descripcion', 'activo'];

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function entrevistados()
    {
        return $this->hasMany(Entrevistado::class);
    }

    public function etapas()
    {
        return $this->hasMany(Etapa::class)->orderBy('orden');
    }
}
