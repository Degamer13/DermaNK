<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Medicamento;

class MedicamentosManager extends Component
{
    use WithPagination;

    public $isOpen = false;
    public $confirmingDeleteId = null;

    public $search = '';
    public $letter = '';

    public $medicamento_id;
    public $patologia, $tipo_medicamento, $presentacion, $nombre, $descripcion;

    protected $rules = [
        'patologia' => 'required|string|max:255',
        'tipo_medicamento' => 'required|string|max:255',
        'presentacion' => 'required|string|max:255',
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
    ];

    // Cuando escribes, reseteamos la página a la 1, PERO NO LA LETRA
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Cuando eliges letra, reseteamos página a la 1, PERO NO EL BUSCADOR
    public function filterByLetter($letra)
    {
        // Si clicas la misma letra que ya estaba, quitamos el filtro (toggle)
        if ($this->letter === $letra) {
            $this->letter = '';
        } else {
            $this->letter = $letra;
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = Medicamento::query();

        // 1. APLICAR BÚSQUEDA GENERAL (Si hay texto escrito)
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('nombre', 'like', '%' . $this->search . '%')
                  ->orWhere('patologia', 'like', '%' . $this->search . '%') // Busca en patología
                  ->orWhere('tipo_medicamento', 'like', '%' . $this->search . '%');
            });
        }

        // 2. Y ADEMÁS... APLICAR FILTRO DE LETRA (Si hay letra seleccionada)
        // Esto filtrará sobre los resultados de la búsqueda anterior
        if (!empty($this->letter)) {
            $query->where('nombre', 'like', $this->letter . '%');
        }

        $medicamentos = $query->orderBy('nombre', 'asc')
            ->paginate(12);

        return view('livewire.medicamentos.medicamentos-manager', compact('medicamentos'));
    }

    // ... (El resto de funciones: create, store, delete, etc. se quedan IGUAL) ...

    public function create() { $this->resetInputFields(); $this->isOpen = true; }
    public function edit($id) {
        $medicamento = Medicamento::findOrFail($id);
        $this->medicamento_id = $id;
        $this->patologia = $medicamento->patologia;
        $this->tipo_medicamento = $medicamento->tipo_medicamento;
        $this->presentacion = $medicamento->presentacion;
        $this->nombre = $medicamento->nombre;
        $this->descripcion = $medicamento->descripcion;
        $this->isOpen = true;
    }
    public function store() {
        $this->validate();
        Medicamento::updateOrCreate(['id' => $this->medicamento_id], [
            'patologia' => $this->patologia,
            'tipo_medicamento' => $this->tipo_medicamento,
            'presentacion' => $this->presentacion,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
        ]);
        session()->flash('message', $this->medicamento_id ? 'Actualizado.' : 'Creado.');
        $this->closeModal();
        $this->resetInputFields();
    }
    public function confirmDelete($id) { $this->confirmingDeleteId = $id; }
    public function delete() {
        if($this->confirmingDeleteId){
            Medicamento::find($this->confirmingDeleteId)->delete();
            session()->flash('message', 'Eliminado.');
            $this->confirmingDeleteId = null;
        }
    }
    public function closeModal() { $this->isOpen = false; $this->resetInputFields(); }
    private function resetInputFields() {
        $this->patologia = ''; $this->tipo_medicamento = ''; $this->presentacion = '';
        $this->nombre = ''; $this->descripcion = '';
        $this->medicamento_id = null; $this->confirmingDeleteId = null;
    }
}
