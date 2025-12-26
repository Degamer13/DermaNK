<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class DatabaseRestorer extends Component
{
    use WithFileUploads;

    public $backupFile;
    public $status = '';
    public $isProcessing = false;

    public function restore()
    {
        // Validación inicial
        $this->validate([
            'backupFile' => 'required|file|mimes:zip,sql|max:51200', // Máx 50MB
        ]);

        $this->isProcessing = true;
        $this->status = 'Iniciando proceso...';

        try {
            // 1. Detectar extensión y definir nombre temporal
            $extension = $this->backupFile->getClientOriginalExtension();
            $filename = 'restore_point.' . $extension;

            // 2. Guardar el archivo en disco 'local' (storage/app/temp)
            // storeAs devuelve la ruta relativa, ej: "temp/restore_point.sql"
            $relativePath = $this->backupFile->storeAs('temp', $filename, 'local');

            // 3. Obtener la ruta absoluta correcta para Windows (C:\laragon\...)
            // Esto soluciona el error de "No such file or directory"
            $fullPath = Storage::disk('local')->path($relativePath);

            // Definir ruta de extracción absoluta
            $extractPath = Storage::disk('local')->path('temp/extracted');

            $sqlFile = null;

            // 4. Lógica según el tipo de archivo
            if ($extension === 'zip') {
                $this->status = 'Descomprimido archivo...';

                $zip = new ZipArchive;
                if ($zip->open($fullPath) === TRUE) {
                    // Limpiar carpeta de extracción previa si existe
                    if(is_dir($extractPath)) {
                        Storage::disk('local')->deleteDirectory('temp/extracted');
                    }

                    $zip->extractTo($extractPath);
                    $zip->close();

                    // Buscar .sql dentro de la carpeta extraída
                    // Primero buscamos en la subcarpeta típica de Spatie (db-dumps)
                    $files = glob($extractPath . '/db-dumps/*.sql');

                    if (empty($files)) {
                         // Si no, buscamos en la raíz de la extracción
                         $files = glob($extractPath . '/*.sql');
                    }

                    if (empty($files)) {
                        throw new \Exception("El ZIP no contiene ningún archivo .sql válido.");
                    }

                    $sqlFile = $files[0];
                } else {
                    throw new \Exception("No se pudo abrir el archivo ZIP.");
                }
            } else {
                // Si el usuario subió directamente el .sql, usamos ese archivo
                $sqlFile = $fullPath;
            }

            // 5. Restaurar la Base de Datos
            if (!$sqlFile || !file_exists($sqlFile)) {
                throw new \Exception("Error interno: No se encuentra el archivo SQL para importar.");
            }

            $this->status = 'Restaurando base de datos...';

            // Desactivar checks de claves foráneas para evitar errores de orden en tablas
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Leer todo el contenido del SQL
            $sql = file_get_contents($sqlFile);

            if (!$sql) {
                throw new \Exception("El archivo SQL está vacío o no se pudo leer.");
            }

            // Ejecutar las sentencias SQL
            DB::unprepared($sql);

            // Reactivar checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // 6. Limpieza de archivos temporales (Borra la carpeta temp completa)
            Storage::disk('local')->deleteDirectory('temp');

            $this->status = '¡Restauración completada con éxito!';
            $this->dispatch('restore-success'); // Evento para el frontend

            // Resetear el input de archivo
            $this->reset('backupFile');

        } catch (\Exception $e) {
            $this->status = 'Error: ' . $e->getMessage();

            // Intento de seguridad por si falló a mitad para no dejar la BD inconsistente con FKs
            try { DB::statement('SET FOREIGN_KEY_CHECKS=1;'); } catch(\Exception $x){}

        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.database-restorer');
    }
}
