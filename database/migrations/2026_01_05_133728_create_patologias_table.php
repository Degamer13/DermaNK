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
    Schema::create('patologias', function (Blueprint $table) {
        $table->id();

        // RELACIÓN: Aquí conectamos con la tabla 'historia_medica'
        // Usamos constrained con el nombre exacto de tu tabla padre.
        // onDelete('cascade') significa que si borras la historia médica,
        // se borran automáticamente todas sus patologías asociadas.
        $table->foreignId('historia_medica_id')
              ->constrained('historia_medica')
              ->onDelete('cascade');

        // DATOS DE LA PATOLOGÍA
        $table->string('nombre'); // Ej: "Hipertensión Arterial"
        $table->text('observaciones')->nullable(); // Ej: "Diagnosticado hace 5 años, toma tratamiento."

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('patologias');
}
};
