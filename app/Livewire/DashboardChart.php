<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DashboardChart extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        $this->calcularEstadisticas();
    }

    public function calcularEstadisticas()
    {
        // PASO 1: Obtener TODAS las patologías agrupadas
        // Quitamos el limit() y la lógica de "Otros"
        $resultados = DB::table('patologias')
            ->select('nombre', DB::raw('count(*) as total'))
            ->whereNotNull('nombre')     // Evitar nulos
            ->where('nombre', '!=', '')  // Evitar vacíos
            ->groupBy('nombre')
            ->orderByDesc('total')       // Ordenar: Las más comunes arriba
            ->get();

        // PASO 2: Asignar directamente a las variables
        // pluck crea un array simple: ['Gripe', 'Covid', ...]
        $this->labels = $resultados->pluck('nombre')->toArray();
        $this->data = $resultados->pluck('total')->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard-chart');
    }
}
