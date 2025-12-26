<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class PermissionsManager extends Component
{
    use WithPagination;

    // Listado
    public $search = '';

    // Modal
    public $isOpen = false;
    public $permissionId = null;
    public $confirmingDeleteId = null;

    // Formulario
    public $name;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- ABRIR MODAL ---
    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetInputFields();
        $this->permissionId = $id;
        $this->isOpen = true;

        $permission = Permission::findOrFail($id);
        $this->name = $permission->name;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->permissionId = null;
        $this->name = '';
        $this->resetValidation();
    }

    // --- GUARDAR ---
    public function save()
    {
        $this->validate([
            'name' => 'required|min:3|unique:permissions,name,' . $this->permissionId
        ]);

        if ($this->permissionId) {
            // Editar
            $permission = Permission::find($this->permissionId);
            $permission->update(['name' => $this->name]);
            session()->flash('message', 'Permiso actualizado correctamente.');
        } else {
            // Crear
            Permission::create(['name' => $this->name]);
            session()->flash('message', 'Permiso creado correctamente.');
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
            $permission = Permission::find($this->confirmingDeleteId);

            // Opcional: Verificar si el permiso está en uso por algún rol antes de borrar
            if ($permission->roles()->count() > 0) {
                session()->flash('error', 'No se puede eliminar: Este permiso está asignado a uno o más roles.');
            } else {
                $permission->delete();
                session()->flash('message', 'Permiso eliminado del sistema.');
            }

            $this->confirmingDeleteId = null;
        }
    }

    public function render()
    {
        $permissions = Permission::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.permisos.permissions-manager', compact('permissions'));
    }
}
