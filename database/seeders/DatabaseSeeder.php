<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Llamamos a los seeders en ORDEN ESTRICTO
        $this->call([
            RoleSeeder::class, // 1. Primero se crean los roles y permisos
            UserSeeder::class, // 2. Luego se crean los usuarios y se les asignan roles
        ]);
    }
}
