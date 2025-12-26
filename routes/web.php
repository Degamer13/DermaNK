<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan; // <--- Importante para ejecutar comandos
use Illuminate\Support\Facades\Storage; // <--- Importante para manejar archivos
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use Illuminate\Support\Str; // <--- AGREGA ESTO ARRIBA DEL ARCHIVO SI NO ESTÁ
use Spatie\DbDumper\Databases\MySql; // <--- IMPORTANTE: Asegúrate de importar esto


use Illuminate\Support\Facades\File;
// --- Importación de Controladores ---
use App\Http\Controllers\RecipeController;

// --- Importación de Modelos ---
use App\Models\HistoriaMedica;

// --- Importación de Componentes Livewire ---
use App\Livewire\HistoriasMedicas as HistoriasMedicasComponent;
use App\Livewire\BuscadorMedico;
use App\Livewire\RecipesManager;
use App\Livewire\UsersManager;
use App\Livewire\RolesManager;
use App\Livewire\PermissionsManager;
use App\Livewire\DatabaseRestorer;


// =============================================================================
// 1. RUTAS PÚBLICAS (Solo redirección)
// =============================================================================

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');


// =============================================================================
// 2. RUTAS PROTEGIDAS (SOLO USUARIOS LOGUEADOS)
// =============================================================================
// Todo lo que esté dentro de este grupo 'auth' es invisible para invitados.

Route::middleware(['auth', 'verified'])->group(function () {

    // --- PANEL PRINCIPAL (DASHBOARD) ---
    Route::get('/dashboard', function () {
        $totalGeneral = HistoriaMedica::count();
        $totalMasculino = HistoriaMedica::where('genero', 'Masculino')->count();
        $totalFemenino = HistoriaMedica::where('genero', 'Femenino')->count();

        return view('dashboard', compact('totalGeneral', 'totalMasculino', 'totalFemenino'));
    })->name('dashboard');


    // --- MÓDULOS CLÍNICOS ---

    // Historias Médicas
    Route::get('/historias-medicas', HistoriasMedicasComponent::class)->name('historias.index');

    // Buscador General
    Route::get('/buscador', BuscadorMedico::class)->name('buscador');

    // Récipes e Impresión
    Route::get('/recipes', RecipesManager::class)->name('recipes.index');
    Route::get('/recipe/pdf/{recipe}', [RecipeController::class, 'pdf'])->name('recipe.pdf');


    // --- SEGURIDAD Y ADMINISTRACIÓN (Protegidos por Roles/Permisos) ---

    // Usuarios (Solo quien tenga permiso 'ver usuarios')
    Route::get('/users', UsersManager::class)
        ->name('users.index')
        ->middleware('can:ver usuarios');

    // Roles (Solo Admin)
    Route::get('/roles', RolesManager::class)
        ->name('roles.index')
        ->middleware('can:ver roles');

    // Permisos (Solo Admin)
    Route::get('/permissions', PermissionsManager::class)
        ->name('permissions.index')
        ->middleware('can:ver permisos');

Route::get('/backup/download', function () {
    set_time_limit(0);

    // 1. Configuración
    $dbName = env('DB_DATABASE', 'dermank_db');
    $user = env('DB_USERNAME', 'root');
    $password = env('DB_PASSWORD', ''); // Si está vacía, no la ponemos en el comando

    // Rutas (Ajusta la versión de MySQL si es necesario)
    $mysqldumpPath = 'C:/laragon/bin/mysql/mysql-8.4.3-winx64/bin/mysqldump.exe';

    // Nombres únicos
    $timestamp = now()->format('Y-m-d-H-i-s');
    $sqlFile = storage_path("app/temp/respaldo-{$timestamp}.sql");
    $zipFile = storage_path("app/temp/DermaNK-{$timestamp}.zip");

    // Crear carpeta temporal si no existe
    if (!File::exists(dirname($sqlFile))) {
        File::makeDirectory(dirname($sqlFile), 0755, true);
    }

    try {
        // 2. CONSTRUIR EL COMANDO PURO (Fuerza Bruta)
        // Usamos comillas para evitar errores con espacios en Windows
        $cmd = "\"{$mysqldumpPath}\"";
        $cmd .= " --user=\"{$user}\"";

        if (!empty($password)) {
            $cmd .= " --password=\"{$password}\"";
        }

        // TRUCO PARA EL ERROR 10106: Forzar protocolo TCP y la IP
        $cmd .= " --host=127.0.0.1 --port=3306 --protocol=tcp";

        // Opciones estándar para evitar bloqueos
        $cmd .= " --single-transaction --routines --triggers";

        // Base de datos y archivo de salida
        $cmd .= " \"{$dbName}\" > \"{$sqlFile}\"";

        // 3. Ejecutar el comando en el sistema
        $output = [];
        $returnVar = null;
        exec($cmd, $output, $returnVar);

        // Verificar si funcionó (0 significa éxito en computación)
        if ($returnVar !== 0 || !file_exists($sqlFile) || filesize($sqlFile) === 0) {
            throw new \Exception("El comando falló (Código $returnVar). Revisa si la ruta de mysqldump es correcta en el código.");
        }

        // 4. Comprimir a ZIP (Para que pese menos)
        $zip = new \ZipArchive;
        if ($zip->open($zipFile, \ZipArchive::CREATE) === TRUE) {
            $zip->addFile($sqlFile, "database.sql");
            $zip->close();
        } else {
            throw new \Exception("No se pudo crear el ZIP.");
        }

        // 5. Limpiar el SQL suelto
        unlink($sqlFile);

        // 6. Descargar y borrar
        return response()->download($zipFile)->deleteFileAfterSend(true);

    } catch (\Exception $e) {
        // Limpiar basura si falla
        if (file_exists($sqlFile)) unlink($sqlFile);
        if (file_exists($zipFile)) unlink($zipFile);

        return back()->with('error', 'Error crítico: ' . $e->getMessage());
    }
})->name('backup.download')->middleware('can:crear respaldo');
    // Restaurar Respaldo (DatabaseRestorer Livewire)
    Route::get('/backup/restore', App\Livewire\DatabaseRestorer::class)
    ->name('backup.restore')
    ->middleware('can:restaurar respaldo'); // O el permiso más alto que tengas

    // --- CONFIGURACIÓN DE PERFIL (Volt / Fortify) ---
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication() &&
                Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                []
            )
        )->name('two-factor.show');
});
