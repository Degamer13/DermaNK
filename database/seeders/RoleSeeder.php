<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resetear la caché de permisos (Vital para evitar errores)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Definir TODOS los permisos del sistema
        $permissions = [
            // --- GESTIÓN DE USUARIOS ---
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',

            // --- GESTIÓN DE ROLES (Seguridad) ---
            'ver roles',
            'crear roles',
            'editar roles',
            'eliminar roles',

            // --- GESTIÓN DE PERMISOS (Seguridad Avanzada) ---
            'ver permisos',
            'crear permisos',
            'editar permisos',
            'eliminar permisos',

            // --- GESTIÓN DE PACIENTES / HISTORIAS ---
            'ver pacientes',
            'crear pacientes',
            'editar pacientes',
            'eliminar pacientes',

            // --- GESTIÓN DE RÉCIPES ---
            'ver recipes',
            'crear recipes',
            'editar recipes',
            'eliminar recipes',
            'descargar recipes', // PDF

            // --- GESTIÓN DE BACKUPS ---
            'crear respaldo',
            'restaurar respaldo',

        ];

        // Crear los permisos en la BD
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 3. Crear Roles y Asignar Permisos

        // === ROL 1: ADMINISTRADOR ===
        // Tiene acceso total a todo lo que existe y existirá
        $roleAdmin = Role::create(['name' => 'Administrador']);
        $roleAdmin->givePermissionTo(Permission::all());

        // === ROL 2: ASISTENTE ===
        // Solo operativo: Pacientes y Récipes (Sin borrar)
        $roleAsistente = Role::create(['name' => 'Asistente']);
        $roleAsistente->givePermissionTo([
            // Pacientes (Puede ver, crear y corregir, pero NO eliminar)
            'ver pacientes',
            'crear pacientes',
            'editar pacientes',

            // Récipes (Puede ver, crear, corregir y descargar, pero NO eliminar)
            'ver recipes',
            'crear recipes',
            'editar recipes',
            'descargar recipes',
        ]);
    }
}
