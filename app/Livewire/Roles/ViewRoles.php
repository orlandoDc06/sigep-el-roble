<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ViewRoles extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedRoleId;
    public $showModal = false;
    public $confirmingRoleDeletion = false;
    public $roleToDelete;

    protected $listeners = ['roleUpdated' => '$refresh'];

    public function getRolesProperty()
    {
        return Role::query()
            ->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($this->search) . '%'])
            ->paginate(10);
    }

    public function showPermissions($roleId)
    {
        $this->selectedRoleId = $roleId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function editRole($id)
    {
        return redirect()->route('admin.roles.edit', ['role' => $id]);
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        session()->flash('message', 'Rol eliminado correctamente');
    }

    public function render()
    {
        
        return view('livewire.roles.view-roles', [
            'roles' => $this->roles,
        ]);
    }

    public function confirmDelete($id)
    {
        $this->confirmingRoleDeletion = true;
        $this->roleToDelete = $id;
    }

    public function deleteConfirmed()
    {
        $role = Role::findOrFail($this->roleToDelete);
        $role->delete();

        $this->confirmingRoleDeletion = false;
        session()->flash('message', 'Rol eliminado correctamente');
    }
}
