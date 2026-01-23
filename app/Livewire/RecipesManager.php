<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Recipe;
use App\Models\HistoriaMedica;
use App\Models\Medicamento; // Importamos el modelo
use Illuminate\Support\Str;

class RecipesManager extends Component
{
    use WithPagination;

    // ... (Tus variables existentes: search, isOpen, recipeId, etc.) ...
    public $search = '';
    public $isOpen = false;
    public $recipeId = null;
    public $confirmingDeleteId = null;
    public $searchPatient = '';
    public $selectedPatient = null;
    public $fecha;
    public $observaciones;

    public $items = [
        ['medicamento' => '', 'indicaciones' => '']
    ];

    public function mount()
    {
        $this->fecha = date('Y-m-d');
    }

    // ... (Tus métodos create, edit, closeModal, resetInputFields iguales) ...
    // ... (Solo pego create y edit resumidos para mantener contexto) ...

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->recipeId = $id;
        $this->isOpen = true;

        $recipe = Recipe::with('items', 'paciente')->findOrFail($id);

        $this->selectedPatient = $recipe->paciente;
        $this->fecha = $recipe->fecha->format('Y-m-d');
        $this->observaciones = $recipe->observaciones;

        $this->items = $recipe->items->map(function($item){
            return [
                'medicamento' => $item->medicamento,
                'indicaciones' => $item->indicaciones
            ];
        })->toArray();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->recipeId = null;
        $this->searchPatient = '';
        $this->selectedPatient = null;
        $this->fecha = date('Y-m-d');
        $this->observaciones = '';
        $this->items = [['medicamento' => '', 'indicaciones' => '']];
        $this->resetValidation();
    }

    // ... (Métodos de Pacientes y addItem/removeItem iguales) ...
    public function selectPatient($id)
    {
        $this->selectedPatient = HistoriaMedica::find($id);
        $this->searchPatient = '';
    }

    public function removePatient()
    {
        $this->selectedPatient = null;
    }

    public function addItem()
    {
        $this->items[] = ['medicamento' => '', 'indicaciones' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    // --- GUARDAR (AQUÍ ESTÁ LA MAGIA DEL NUEVO REQUERIMIENTO) ---
    public function save()
    {
        $this->validate([
            'selectedPatient' => 'required',
            'fecha' => 'required|date',
            'items.*.medicamento' => 'required|min:2',
            'items.*.indicaciones' => 'required|min:2',
        ]);

        if ($this->recipeId) {
            $recipe = Recipe::find($this->recipeId);
            $recipe->update([
                'historia_medica_id' => $this->selectedPatient->id,
                'fecha' => $this->fecha,
                'observaciones' => $this->observaciones,
            ]);
            $recipe->items()->delete();
        } else {
            $recipe = Recipe::create([
                'historia_medica_id' => $this->selectedPatient->id,
                'codigo' => 'REC-' . strtoupper(Str::random(6)),
                'fecha' => $this->fecha,
                'observaciones' => $this->observaciones,
            ]);
        }

        // Guardar Items y Registrar Medicamentos Nuevos
        foreach ($this->items as $item) {

            // 1. Buscamos o creamos el medicamento en el catálogo general
            // 'firstOrCreate' busca por 'nombre'. Si no existe, lo crea.
            Medicamento::firstOrCreate(
                ['nombre' => $item['medicamento']], // Criterio de búsqueda
                [
                    // Valores por defecto si se crea uno nuevo (opcional)
                    'descripcion' => 'Registrado desde Récipe',
                    'tipo_medicamento' => 'Generico'
                ]
            );

            // 2. Guardamos el item en el récipe (texto plano tal cual estaba)
            $recipe->items()->create($item);
        }

        session()->flash('message', 'Récipe guardado correctamente.');
        $this->closeModal();
    }

    // ... (Métodos delete e confirmDelete iguales) ...
    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmingDeleteId) {
            Recipe::find($this->confirmingDeleteId)->delete();
            session()->flash('message', 'Récipe eliminado.');
            $this->confirmingDeleteId = null;
        }
    }

    public function render()
    {
        $recipes = Recipe::with('paciente')
            ->where('codigo', 'like', '%' . $this->search . '%')
            ->orWhereHas('paciente', function ($q) {
                $q->where('nombres', 'like', '%' . $this->search . '%')
                  ->orWhere('cedula', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $patientsFound = [];
        if (strlen($this->searchPatient) > 1) {
            $patientsFound = HistoriaMedica::where('nombres', 'like', "%{$this->searchPatient}%")
                ->orWhere('cedula', 'like', "%{$this->searchPatient}%")
                ->take(5)->get();
        }

        // NUEVO: Obtenemos solo los nombres de los medicamentos para el autocompletado
        // Usamos pluck para que sea un array simple ['Aspirina', 'Ibuprofeno', ...]
        $medicamentosList = Medicamento::orderBy('nombre')->pluck('nombre');

        return view('livewire.recipes.recipes-manager', compact('recipes', 'patientsFound', 'medicamentosList'));
    }
}
