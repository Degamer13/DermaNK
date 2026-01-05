<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Importante para calcular la edad

class AgeStatsChart extends Component
{
    public $labels = ['Menores de Edad (<18)', 'Adultos (18-59)', 'Tercera Edad (60+)'];
    public $data = [];

    public function mount()
    {
        $this->calcularEdades();
    }

    public function calcularEdades()
    {
        // 1. Traemos solo las fechas de nacimiento para no cargar memoria innecesaria
        $pacientes = DB::table('historia_medica')
            ->select('fecha_nacimiento')
            ->get();

        $menores = 0;
        $adultos = 0;
        $terceraEdad = 0;

        // 2. Recorremos y calculamos la edad de cada uno
        foreach ($pacientes as $paciente) {
            // Carbon calcula la edad automáticamente
            $edad = Carbon::parse($paciente->fecha_nacimiento)->age;

            if ($edad < 18) {
                $menores++;
            } elseif ($edad >= 60) {
                $terceraEdad++;
            } else {
                $adultos++; // Entre 18 y 59
            }
        }

        // 3. Pasamos los totales al array de datos
        $this->data = [$menores, $adultos, $terceraEdad];
    }

    public function render()
    {
        return view('livewire.age-stats-chart');
    }
}
