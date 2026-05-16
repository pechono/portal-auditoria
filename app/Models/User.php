<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
        protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'rol',
        'activo',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_usuario');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'solicitante_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function esDocente(): bool
    {
        return $this->rol === 'docente';
    }

    public function esAlumno(): bool
    {
        return $this->rol === 'alumno';
    }

}
