<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear al Director (Usuario Único)
        User::create([
            'name' => 'Director General',
            'email' => 'director@escuela.com',
            'password' => Hash::make('12345678'), // Contraseña
            'role' => 'director', // Rol Importante
        ]);

        // 2. Crear un Profesor de prueba
        User::create([
            'name' => 'Profesor Juan',
            'email' => 'profe@escuela.com',
            'password' => Hash::make('12345678'),
            'role' => 'profesor',
        ]);
    }
}