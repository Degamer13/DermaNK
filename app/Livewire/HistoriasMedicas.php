<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HistoriaMedica;
// No olvides importar el modelo hijo si fuera necesario, aunque usamos la relación.

class HistoriasMedicas extends Component
{
    use WithPagination;

    public $search = '';
    public $view = 'list';

    public $historia_id = null;
    public $historiaSeleccionada = null;
    public $confirmingDeleteId = null;

    // Propiedades del paciente
    public $cedula, $nombres, $apellidos, $fecha_nacimiento, $lugar_nacimiento;
    public $direccion, $telefono, $telefono_casa, $email;
    public $profesion, $ocupacion, $referido, $estado_civil, $genero, $seguro, $medico;

    // NUEVO: Array para manejar múltiples patologías
    public $patologias = [];

    protected function rules()
    {
        return [
            'cedula' => 'required|string|unique:historia_medica,cedula,' . $this->historia_id,
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'lugar_nacimiento' => 'required|string',
            'direccion' => 'required|string',
            'telefono' => 'required|string',
            'ocupacion' => 'required|string',
            'estado_civil' => 'required|string',
            'genero' => 'required|string',
            'seguro' => 'required|string',
            'medico' => 'required|string',
            'telefono_casa' => 'nullable|string',
            'email' => 'nullable|email',
            'profesion' => 'nullable|string',
            'referido' => 'nullable|string',

            // NUEVO: Validación para el array de patologías
            'patologias.*.nombre' => 'required|string', // El nombre es obligatorio si agregas una fila
            'patologias.*.observaciones' => 'nullable|string',
        ];
    }

    // --- MÉTODOS PARA GESTIONAR PATOLOGÍAS DINÁMICAS ---

    // Agregar una fila vacía al array
    public function addPatologia()
    {
        $this->patologias[] = ['nombre' => '', 'observaciones' => ''];
    }

    // Quitar una fila específica del array
    public function removePatologia($index)
    {
        unset($this->patologias[$index]);
        $this->patologias = array_values($this->patologias); // Reordenar índices
    }

    // ---------------------------------------------------

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        // Opcional: Iniciar con una fila vacía de patología si quieres
        // $this->addPatologia();
        $this->view = 'form';
    }

    public function show($id)
    {
        // Al mostrar, cargamos la relación 'patologias' para poder verlas
        $this->historiaSeleccionada = HistoriaMedica::with('patologias')->findOrFail($id);
        $this->view = 'show';
    }

    public function store()
    {
        $this->validate();

        // 1. Guardamos o Actualizamos la Historia Médica (Padre)
        $historia = HistoriaMedica::updateOrCreate(['id' => $this->historia_id], [
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

        // 2. Guardamos las Patologías (Hijas)
        // Estrategia: Borramos las anteriores y creamos las nuevas para evitar duplicados o lógica compleja de IDs
        $historia->patologias()->delete();

        if (!empty($this->patologias)) {
            // createMany espera un array de arrays asociativos
            $historia->patologias()->createMany($this->patologias);
        }

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

        // NUEVO: Cargar las patologías existentes al array para editarlas
        // Mapeamos solo los campos que necesitamos
        $this->patologias = $historia->patologias->map(function($patologia) {
            return [
                'nombre' => $patologia->nombre,
                'observaciones' => $patologia->observaciones
            ];
        })->toArray();

        $this->view = 'form';
    }

    // LÓGICA DEL MODAL DE ELIMINACIÓN
    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmingDeleteId) {
            // Al borrar la historia, las patologías se borran solas si pusiste onDelete('cascade') en la migración
            HistoriaMedica::find($this->confirmingDeleteId)->delete();
            session()->flash('message', 'Historia eliminada correctamente.');
            $this->confirmingDeleteId = null;
            $this->resetPage();
        }
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function cancel()
    {
        $this->resetInputFields();
        $this->historiaSeleccionada = null;
        $this->view = 'list';
    }

    private function resetInputFields()
    {
        $this->historia_id = null;
        // Reseteamos también el array de patologías
        $this->patologias = [];
        $this->reset([
            'cedula', 'nombres', 'apellidos', 'fecha_nacimiento', 'lugar_nacimiento',
            'direccion', 'telefono', 'telefono_casa', 'email', 'profesion',
            'ocupacion', 'referido', 'estado_civil', 'genero', 'seguro', 'medico'
        ]);
    }

   public function render()
    {
        // Limpiamos el input para intentar detectar si buscan por ID (HM-001 -> 1)
        $busquedaId = ltrim(str_ireplace(['HM-', 'hm-'], '', $this->search), '0');

        $historias = HistoriaMedica::where(function($query) use ($busquedaId) {
                // Búsqueda básica (Nombres, Apellidos, Cédula)
                $query->where('nombres', 'like', '%' . $this->search . '%')
                      ->orWhere('apellidos', 'like', '%' . $this->search . '%')
                      ->orWhere('cedula', 'like', '%' . $this->search . '%');

                // --- NUEVO: BUSCAR POR NOMBRE DE PATOLOGÍA ---
                // Esto busca si la historia tiene AL MENOS UNA patología que coincida con el texto
                $query->orWhereHas('patologias', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%');
                });
                // ---------------------------------------------

                // Búsqueda por ID numérico (si aplica)
                if (is_numeric($busquedaId) && $busquedaId != '') {
                    $query->orWhere('id', 'like', '%' . $busquedaId . '%');
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.historias-medicas.historias-medicas', ['historias' => $historias]);
    }
}
