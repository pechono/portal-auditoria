<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DocenteSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'nombre'   => 'Pablo Nicolas',
            'apellido' => 'Balsamo',
            'email'    => 'balsamo@auditoria.edu.ar',
            'password' => Hash::make('docente1234'),
            'rol'      => 'docente',
            'activo'   => true,
        ]);
    }
}