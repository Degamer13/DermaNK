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
    Schema::create('recipes', function (Blueprint $table) {
        $table->id();
        // Relación con el Paciente
        $table->foreignId('historia_medica_id')->constrained('historia_medica')->onDelete('cascade');

        $table->string('codigo')->unique(); // Ej: REC-A8J2P
        $table->date('fecha');
        $table->text('observaciones')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
