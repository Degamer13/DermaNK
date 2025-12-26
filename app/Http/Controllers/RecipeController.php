<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Barryvdh\DomPDF\Facade\Pdf;

class RecipeController extends Controller
{
    public function pdf(Recipe $recipe)
    {
        // Cargamos las relaciones para optimizar
        $recipe->load(['items', 'paciente']);

        // Renderizamos la vista del PDF
        $pdf = Pdf::loadView('pdf.recipe', compact('recipe'));

        // Mostramos el PDF en el navegador
        return $pdf->stream('Recipe-' . $recipe->codigo . '.pdf');
    }
}
