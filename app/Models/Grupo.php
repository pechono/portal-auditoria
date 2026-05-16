<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = ['caso_id', 'nombre', 'estado'];

    public function caso()
    {
        return $this->belongsTo(Caso::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'grupo_usuario');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class);
    }
}