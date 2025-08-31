<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class ManageRoles extends Component
{
    public $roleName;
    public $selectedPermissions = [];
    public $permissions;
    public $permissionSearch = '';

    public $editingRoleId = null;

    public $newPermissionName = '';
    public $editingPermissionId = null;
    public $showPermissionForm = false;
    public $showDeleteModal = false;
    public $permissionToDelete = null;

    protected $listeners = ['createRole' => 'resetForm', 'editRole' => 'loadRole'];

    public function mount($role = null)
    {
        $this->permissions = Permission::all();

        if ($role) {
            $this->loadRole($role);
        } else {
            $this->resetForm();
        }
    }

    public function createPermission()
    {
        $this->validate([
            'newPermissionName' => [
                'required',
                'string',
                Rule::unique('permissions', 'name')->ignore($this->editingPermissionId ?? 0),
            ],
        ]);

        if ($this->editingPermissionId) {
            $permission = Permission::find($this->editingPermissionId);
            if ($permission) {
                $permission->update(['name' => $this->newPermissionName]);
            }
        } else {
            $permission = Permission::create(['name' => $this->newPermissionName]);
            $this->permissions->push($permission);
        }

        $this->resetPermissionForm();
    }

    public function editPermission($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $this->newPermissionName = $permission->name;
        $this->editingPermissionId = $permission->id;
        $this->showPermissionForm = true;
    }

    public function confirmDeletePermission($permissionId)
    {
        $this->permissionToDelete = $permissionId;
        $this->showDeleteModal = true;
    }

    public function deletePermission()
    {
        $permission = Permission::find($this->permissionToDelete);
        if ($permission) {
            $permission->delete();
            $this->permissions = $this->permissions->filter(fn($p) => $p->id !== $permission->id);
            $this->selectedPermissions = array_values(array_filter($this->selectedPermissions, fn($id) => $id != $permission->id));
        }
        $this->showDeleteModal = false;
        $this->permissionToDelete = null;
    }

    public function resetPermissionForm()
    {
        $this->newPermissionName = '';
        $this->editingPermissionId = null;
        $this->showPermissionForm = false;
    }

    public function addPermission($permissionId)
    {
        if (!in_array($permissionId, $this->selectedPermissions)) {
            $this->selectedPermissions[] = $permissionId;
        }
    }

    public function removePermission($permissionId)
    {
        $this->selectedPermissions = array_values(array_filter(
            $this->selectedPermissions,
            fn($id) => $id != $permissionId
        ));
    }

    public function storeRole()
    {
        $this->roleName = trim($this->roleName);
        $this->validate([
            'roleName' => [
                'required',
                'string',
                Rule::unique('roles', 'name')->ignore($this->editingRoleId),
            ],
            'selectedPermissions' => 'required|array|min:1',
        ]);

        $isEditing = $this->editingRoleId !== null;

        if ($isEditing) {
            $role = Role::findOrFail($this->editingRoleId);
            $role->update(['name' => $this->roleName]);
        } else {
            $role = Role::create(['name' => $this->roleName]);
        }

        $role->syncPermissions($this->selectedPermissions);

        $message = $isEditing ? 'Rol actualizado exitosamente.' : 'Rol creado exitosamente.';

        $this->resetForm();

        return redirect()->route('admin.roles.index')
            ->with('success', $message);
    }

    public function resetForm()
    {
        $this->reset(['roleName', 'selectedPermissions', 'editingRoleId']);
        $this->resetPermissionForm();
    }

    public function loadRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->roleName = $role->name;
        $this->editingRoleId = $role->id;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
    }

    public function render()
    {
        return view('livewire.roles.manage-roles');
    }
}
