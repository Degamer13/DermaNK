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
    Schema::create('recipe_items', function (Blueprint $table) {
        $table->id();
        // Relación con el Récipe
        $table->foreignId('recipe_id')->constrained('recipes')->onDelete('cascade');

        $table->string('medicamento');
        $table->text('indicaciones'); // Dosis, frecuencia...

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_items');
    }
};
