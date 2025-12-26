<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesManager extends Component
{
    use WithPagination;

    // Listado
    public $search = '';

    // Modal y CRUD
    public $isOpen = false;
    public $roleId = null;
    public $confirmingDeleteId = null;

    // Campos del Formulario
    public $name;
    public $selectedPermissions = []; // Aquí guardaremos los IDs de los permisos marcados

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
        $this->roleId = $id;
        $this->isOpen = true;

        $role = Role::findOrFail($id);
        $this->name = $role->name;

        // Cargamos los permisos que ya tiene este rol (pluck obtiene solo los IDs)
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->roleId = null;
        $this->name = '';
        $this->selectedPermissions = [];
        $this->resetValidation();
    }

    // --- GUARDAR ---
    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'required|array|min:1' // Obligar a marcar al menos uno
        ]);

        if ($this->roleId) {
            // EDITAR
            $role = Role::find($this->roleId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions); // Actualizar permisos
            session()->flash('message', 'Rol actualizado correctamente.');
        } else {
            // CREAR
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions); // Asignar permisos
            session()->flash('message', 'Rol creado correctamente.');
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
            $role = Role::find($this->confirmingDeleteId);

            // Protección: No dejar borrar al Admin
            if($role->name === 'admin') {
                session()->flash('error', 'No puedes eliminar el rol de Administrador.');
            } else {
                $role->delete();
                session()->flash('message', 'Rol eliminado.');
            }

            $this->confirmingDeleteId = null;
        }
    }

    public function render()
    {
        $roles = Role::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Obtenemos todos los permisos para mostrarlos en el formulario
        $permissions = Permission::all();

        return view('livewire.roles.roles-manager', compact('roles', 'permissions'));
    }
}
