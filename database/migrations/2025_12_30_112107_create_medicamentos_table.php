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
    Schema::create('medicamentos', function (Blueprint $table) {
        $table->id();
        $table->string('nombre'); // Ej: Acetaminofén
        $table->string('descripcion')->nullable(); // Ej: Para el dolor de cabeza
        $table->string('patologia')->nullable(); // Ej: Cefalea, Dermatitis
        $table->string('tipo_medicamento')->nullable(); // Ej: Analgésico, Antibiótico
        $table->string('presentacion')->nullable(); // Ej: Tableta 500mg, Jarabe
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
