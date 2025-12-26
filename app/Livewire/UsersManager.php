<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role; // Importante
use Illuminate\Support\Facades\Hash;

class UsersManager extends Component
{
    use WithPagination;

    // Búsqueda y Modal
    public $search = '';
    public $isOpen = false;
    public $userId = null;
    public $confirmingDeleteId = null;

    // Campos del Formulario
    public $name, $email, $password, $role;

    // Resetear paginación al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- ABRIR MODAL (CREAR) ---
    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    // --- ABRIR MODAL (EDITAR) ---
    public function edit($id)
    {
        $this->resetInputFields();
        $this->userId = $id;
        $this->isOpen = true;

        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        // Obtenemos el primer rol que tenga el usuario (si tiene)
        $this->role = $user->roles->first()?->name;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = '';
        $this->resetValidation();
    }

    // --- GUARDAR ---
    public function save()
    {
        // Validaciones dinámicas
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required'
        ];

        // Si es crear, password obligatorio. Si es editar, opcional.
        if (!$this->userId) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        if ($this->userId) {
            // EDITAR
            $user = User::find($this->userId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];
            // Solo actualizamos la contraseña si escribieron algo nuevo
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);

            // Sincronizar Rol (Spatie)
            $user->syncRoles($this->role);

            session()->flash('message', 'Usuario actualizado correctamente.');
        } else {
            // CREAR
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Asignar Rol (Spatie)
            $user->assignRole($this->role);

            session()->flash('message', 'Usuario creado correctamente.');
        }

        $this->closeModal();
    }

    // --- ELIMINAR ---
    public function confirmDelete($id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function delete()
    {
        if ($this->confirmingDeleteId) {
            // Evitar auto-borrarse
            if($this->confirmingDeleteId == auth()->id()) {
                session()->flash('error', 'No puedes eliminar tu propia cuenta.');
            } else {
                User::find($this->confirmingDeleteId)->delete();
                session()->flash('message', 'Usuario eliminado.');
            }
            $this->confirmingDeleteId = null;
        }
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        // Pasamos los roles disponibles para el select
        $roles = Role::all();

        // IMPORTANTE: Recuerda poner la ruta correcta de tu vista si usas carpetas
        return view('livewire.usuarios.users-manager', compact('users', 'roles'));
    }
}
