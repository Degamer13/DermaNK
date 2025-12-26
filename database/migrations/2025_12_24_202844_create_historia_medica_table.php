<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historia_medica', function (Blueprint $table) {
            $table->id();

            // Identificación
            // Usamos string en cédula para permitir formatos como "V-12345678" o ceros a la izquierda
            $table->string('cedula')->unique();
            $table->string('nombres');
            $table->string('apellidos');

            // Datos de Nacimiento
            $table->date('fecha_nacimiento');
            $table->string('lugar_nacimiento');

            // Contacto y Ubicación
            $table->text('direccion'); // 'text' permite direcciones más largas

            // Teléfonos: Se recomienda string, pero si requieres numérico usa bigInteger para evitar errores de desbordamiento
            $table->string('telefono');
            $table->string('telefono_casa')->nullable();

            $table->string('email')->nullable();

            // Información Socioeconómica
            $table->string('profesion')->nullable();
            $table->string('ocupacion'); // No indicaste null, así que es obligatorio
            $table->string('referido')->nullable();

            // Detalles Personales
            // Para género y estado civil, string funciona bien (o podrías usar enum en el futuro)
            $table->string('estado_civil');
            $table->string('genero');

            // Información Médica Administrativa
            $table->string('seguro'); // Asumido obligatorio según tu lista
            $table->string('medico'); // Nombre del médico tratante

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_medica');
    }
};
