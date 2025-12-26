<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HistoriaMedica;

class HistoriasMedicas extends Component
{
    use WithPagination;

    public $search = '';
    public $view = 'list'; // 'list', 'form', o 'show'

    // Identificador del registro que estamos editando
    public $historia_id = null;

    // Objeto para ver detalles (Show)
    public $historiaSeleccionada = null;

    // NUEVO: Propiedad para manejar el Modal de Eliminación
    public $confirmingDeleteId = null;

    // Propiedades del formulario
    public $cedula, $nombres, $apellidos, $fecha_nacimiento, $lugar_nacimiento;
    public $direccion, $telefono, $telefono_casa, $email;
    public $profesion, $ocupacion, $referido, $estado_civil, $genero, $seguro, $medico;

    protected function rules()
    {
        return [
            'cedula' => 'required|string|unique:historia_medica,cedula,' . $this->historia_id,
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'lugar_nacimiento' => 'required|string',
            'direccion' => 'required|string',
            'telefono' => 'required|numeric',
            'ocupacion' => 'required|string',
            'estado_civil' => 'required|string',
            'genero' => 'required|string',
            'seguro' => 'required|string',
            'medico' => 'required|string',
            'telefono_casa' => 'nullable|numeric',
            'email' => 'nullable|email',
            'profesion' => 'nullable|string',
            'referido' => 'nullable|string',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->view = 'form';
    }

    public function show($id)
    {
        $this->historiaSeleccionada = HistoriaMedica::findOrFail($id);
        $this->view = 'show';
    }

    public function store()
    {
        $this->validate();

        HistoriaMedica::updateOrCreate(['id' => $this->historia_id], [
            'cedula' => $this->cedula,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'lugar_nacimiento' => $this->lugar_nacimiento,
            'direccion' => $this->direccion,
            'telefono' => $this->telefono,
            'telefono_casa' => $this->telefono_casa,
            'email' => $this->email,
            'profesion' => $this->profesion,
            'ocupacion' => $this->ocupacion,
            'referido' => $this->referido,
            'estado_civil' => $this->estado_civil,
            'genero' => $this->genero,
            'seguro' => $this->seguro,
            'medico' => $this->medico,
        ]);

        session()->flash('message', $this->historia_id ? 'Historia actualizada correctamente.' : 'Historia creada correctamente.');
        $this->cancel();
    }

    public function edit($id)
    {
        $historia = HistoriaMedica::findOrFail($id);

        $this->historia_id = $id;
        $this->cedula = $historia->cedula;
        $this->nombres = $historia->nombres;
        $this->apellidos = $historia->apellidos;
        $this->fecha_nacimiento = $historia->fecha_nacimiento->format('Y-m-d');
        $this->lugar_nacimiento = $historia->lugar_nacimiento;
        $this->direccion = $historia->direccion;
        $this->telefono = $historia->telefono;
        $this->telefono_casa = $historia->telefono_casa;
        $this->email = $historia->email;
        $this->profesion = $historia->profesion;
        $this->ocupacion = $historia->ocupacion;
        $this->referido = $historia->referido;
        $this->estado_civil = $historia->estado_civil;
        $this->genero = $historia->genero;
        $this->seguro = $historia->seguro;
        $this->medico = $historia->medico;

        $this->view = 'form';
    }

    // --- LÓGICA DEL MODAL DE ELIMINACIÓN ---

    // 1. Abre el modal
    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    // 2. Ejecuta la eliminación real
    public function delete()
    {
        if ($this->confirmingDeleteId) {
            HistoriaMedica::find($this->confirmingDeleteId)->delete();
            session()->flash('message', 'Historia eliminada correctamente.');
            $this->confirmingDeleteId = null; // Cierra modal
            $this->resetPage(); // Opcional: seguridad de paginación
        }
    }

    // 3. Cancela y cierra
    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    // ----------------------------------------

    public function cancel()
    {
        $this->resetInputFields();
        $this->historiaSeleccionada = null;
        $this->view = 'list';
    }

    private function resetInputFields()
    {
        $this->historia_id = null;
        $this->reset([
            'cedula', 'nombres', 'apellidos', 'fecha_nacimiento', 'lugar_nacimiento',
            'direccion', 'telefono', 'telefono_casa', 'email', 'profesion',
            'ocupacion', 'referido', 'estado_civil', 'genero', 'seguro', 'medico'
        ]);
    }

   public function render()
    {
        // 1. Preparamos el término de búsqueda para el ID
        // Quitamos "HM-" (mayúscula o minúscula) y los ceros a la izquierda.
        // Ejemplo: Si escribe "HM-000050", esto lo convierte en "50".
        $busquedaId = ltrim(str_ireplace(['HM-', 'hm-'], '', $this->search), '0');

        $historias = HistoriaMedica::where(function($query) use ($busquedaId) {
                // Búsquedas de texto normal (usamos $this->search original)
                $query->where('nombres', 'like', '%' . $this->search . '%')
                      ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                      ->orWhere('cedula', 'like', '%' . $this->search . '%');

                // 2. Agregamos la búsqueda por ID numérico
                // Solo si $busquedaId es un número válido, buscamos en la columna 'id'
                if (is_numeric($busquedaId) && $busquedaId != '') {
                    $query->orWhere('id', 'like', '%' . $busquedaId . '%');
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.historias-medicas.historias-medicas', ['historias' => $historias]);
    }
}
