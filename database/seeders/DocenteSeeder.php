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
            'email'    => 'pbalsamo@unlar.edu.ar',
            'password' => Hash::make('FelipeyLynn@1'),
            'rol'      => 'docente',
            'activo'   => true,
        ]);
    }
}