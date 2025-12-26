<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\HistoriaMedica;
use App\Models\Recipe;
use Illuminate\Support\Str;

class CrearRecipe extends Component
{
    public $historia_id;
    public $paciente;

    // Array dinámico: Aquí iremos registrando medicamentos e indicaciones
    public $items = [
        ['medicamento' => '', 'dosis' => '']
    ];

    public function mount($historia_id)
    {
        $this->historia_id = $historia_id;
        $this->paciente = HistoriaMedica::findOrFail($historia_id);
    }

    // Botón [+] Agregar otro medicamento
    public function addItem()
    {
        $this->items[] = ['medicamento' => '', 'dosis' => ''];
    }

    // Botón [Basurero] Quitar fila
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items); // Reordenar índices
    }

    // Acción Final: Guardar y Generar PDF
    public function save()
    {
        // 1. Validamos que no haya campos vacíos
        $this->validate([
            'items.*.medicamento' => 'required|string|min:3',
            'items.*.dosis' => 'required|string|min:3',
        ], [
            'items.*.medicamento.required' => 'Indica el medicamento.',
            'items.*.dosis.required' => 'Indica la dosis/instrucción.',
        ]);

        // 2. Guardamos el Encabezado (El Récipe)
        $recipe = Recipe::create([
            'historia_medica_id' => $this->historia_id,
            'codigo' => 'REC-' . strtoupper(Str::random(8)), // O usa un consecutivo si prefieres
        ]);

        // 3. Guardamos cada Medicamento (Detalles)
        foreach ($this->items as $item) {
            $recipe->items()->create([
                'medicamento' => $item['medicamento'],
                'dosis' => $item['dosis']
            ]);
        }

        // 4. ¡MAGIA! Redirigimos directo al PDF generado
        return redirect()->route('recipe.pdf', $recipe->id);
    }

    public function render()
    {
        return view('livewire.recipes.crear-recipe');
    }
}
