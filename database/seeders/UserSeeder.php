<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. USUARIO ADMINISTRADOR (Dueño del sistema)
        $admin = User::create([
            'name' => 'Administrador Principal',
            'email' => 'admin@dermank.com',
            'password' => Hash::make('password'), // Contraseña: password
            'email_verified_at' => now(), // Para que no pida verificar email
        ]);

        // Le asignamos el rol 'admin' (Tiene todos los permisos)
        $admin->assignRole('Administrador');


        // 2. USUARIO ASISTENTE (Personal operativo)
        $asistente = User::create([
            'name' => 'Asistente de Recepción',
            'email' => 'asistente@dermank.com',
            'password' => Hash::make('password'), // Contraseña: password
            'email_verified_at' => now(),
        ]);

        // Le asignamos el rol 'asistente' (Limitado)
        $asistente->assignRole('Asistente');
    }
}
