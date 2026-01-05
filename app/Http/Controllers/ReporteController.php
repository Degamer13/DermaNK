<?php

namespace App\Http\Controllers;

use App\Models\HistoriaMedica;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function imprimirHistoriaIndividual($id)
    {
        // Buscamos la historia y sus patologías
        $historia = HistoriaMedica::with('patologias')->findOrFail($id);

        // Cargamos la vista del PDF (la crearemos en el paso 2)
        $pdf = Pdf::loadView('pdf.historia-individual', compact('historia'));

        // Formato Carta Vertical (Portrait)
        $pdf->setPaper('letter', 'portrait');

        // stream() abre el PDF en el navegador para imprimir.
        // Si prefieres que se baje directo usa ->download()
        return $pdf->stream("ficha_medica_{$historia->codigo_historia}.pdf");
    }
}
