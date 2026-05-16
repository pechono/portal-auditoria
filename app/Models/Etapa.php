<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etapa extends Model
{
    protected $fillable = [
        'caso_id',
        'numero',
        'nombre',
        'descripcion',
        'orden'
    ];

    public function caso()
    {
        return $this->belongsTo(Caso::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}
