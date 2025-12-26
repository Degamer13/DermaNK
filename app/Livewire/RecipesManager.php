<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Recipe;
use App\Models\HistoriaMedica;
use Illuminate\Support\Str;

class RecipesManager extends Component
{
    use WithPagination;

    // Listado
    public $search = '';

    // Modal y Edición
    public $isOpen = false;
    public $recipeId = null;
    public $confirmingDeleteId = null;

    // Formulario
    public $searchPatient = '';     // Buscador dentro del modal
    public $selectedPatient = null; // Paciente elegido
    public $fecha;
    public $observaciones;

    // Items Dinámicos (Medicamentos)
    public $items = [
        ['medicamento' => '', 'indicaciones' => '']
    ];

    public function mount()
    {
        $this->fecha = date('Y-m-d');
    }

    // --- Abrir Modal para CREAR ---
    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    // --- Abrir Modal para EDITAR ---
    public function edit($id)
    {
        $this->resetInputFields();
        $this->recipeId = $id;
        $this->isOpen = true;

        $recipe = Recipe::with('items', 'paciente')->findOrFail($id);

        $this->selectedPatient = $recipe->paciente;
        $this->fecha = $recipe->fecha->format('Y-m-d');
        $this->observaciones = $recipe->observaciones;

        // Cargamos los medicamentos existentes al array
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

    // --- Lógica de Pacientes ---
    public function selectPatient($id)
    {
        $this->selectedPatient = HistoriaMedica::find($id);
        $this->searchPatient = '';
    }

    public function removePatient()
    {
        $this->selectedPatient = null;
    }

    // --- Lógica de Medicamentos (Agregar/Quitar Filas) ---
    public function addItem()
    {
        $this->items[] = ['medicamento' => '', 'indicaciones' => ''];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

   // --- GUARDAR ---
    public function save()
    {
        $this->validate([
            'selectedPatient' => 'required',
            'fecha' => 'required|date',
            'items.*.medicamento' => 'required|min:2',
            'items.*.indicaciones' => 'required|min:2',
        ]);

        if ($this->recipeId) {
            // Update (Editar)
            $recipe = Recipe::find($this->recipeId);
            $recipe->update([
                'historia_medica_id' => $this->selectedPatient->id,
                'fecha' => $this->fecha,
                'observaciones' => $this->observaciones,
            ]);
            $recipe->items()->delete(); // Borramos items viejos
        } else {
            // Create (Crear Nuevo)
            $recipe = Recipe::create([
                'historia_medica_id' => $this->selectedPatient->id,
                'codigo' => 'REC-' . strtoupper(Str::random(6)),
                'fecha' => $this->fecha,
                'observaciones' => $this->observaciones,
            ]);
        }

        // Guardar Items
        foreach ($this->items as $item) {
            $recipe->items()->create($item);
        }

        // 1. Mensaje de éxito (Flash Message)
        session()->flash('message', 'Récipe guardado correctamente.');

        // 2. Cerrar el Modal
        $this->closeModal();

        // 3. ¡LISTO! Ya no hacemos el return redirect.
        // El componente se recargará solo mostrando la tabla y el mensaje verde.
    }

    // --- ELIMINAR ---
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
        // Consulta principal para la tabla
        $recipes = Recipe::with('paciente')
            ->where('codigo', 'like', '%' . $this->search . '%')
            ->orWhereHas('paciente', function ($q) {
                $q->where('nombres', 'like', '%' . $this->search . '%')
                  ->orWhere('cedula', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Búsqueda de pacientes para el Dropdown
        $patientsFound = [];
        if (strlen($this->searchPatient) > 1) {
            $patientsFound = HistoriaMedica::where('nombres', 'like', "%{$this->searchPatient}%")
                ->orWhere('cedula', 'like', "%{$this->searchPatient}%")
                ->take(5)->get();
        }

        return view('livewire.recipes.recipes-manager', compact('recipes', 'patientsFound'));
    }
}
