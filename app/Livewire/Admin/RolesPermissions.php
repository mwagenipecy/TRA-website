<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class RolesPermissions extends Component
{
    use WithPagination;

    public $activeTab = 'roles';
    public $search = '';
    public $showCreateRoleModal = false;
    public $showEditRoleModal = false;
    public $showDeleteRoleModal = false;
    public $showCreatePermissionModal = false;
    public $showEditPermissionModal = false;
    public $showDeletePermissionModal = false;
    public $showAssignRoleModal = false;
    
    // Role form fields
    public $roleId;
    public $roleName = '';
    public $roleDisplayName = '';
    public $roleDescription = '';
    public $rolePermissions = [];
    public $isSystemRole = false;
    public $isActive = true;
    
    // Permission form fields
    public $permissionId;
    public $permissionName = '';
    public $permissionDisplayName = '';
    public $permissionDescription = '';
    public $permissionCategory = '';
    public $isSystemPermission = false;
    
    // Assign role fields
    public $selectedUserId;
    public $selectedRoleId;
    public $additionalPermissions = [];
    public $revokedPermissions = [];

    protected $rules = [
        'roleName' => 'required|string|max:255|unique:roles,name',
        'roleDisplayName' => 'required|string|max:255',
        'roleDescription' => 'nullable|string',
        'permissionName' => 'required|string|max:255|unique:permissions,name',
        'permissionDisplayName' => 'required|string|max:255',
        'permissionDescription' => 'nullable|string',
        'permissionCategory' => 'required|string|max:255',
    ];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Role Methods
    public function createRole()
    {
        $this->resetRoleForm();
        $this->showCreateRoleModal = true;
    }

    public function editRole($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->roleDisplayName = $role->display_name;
        $this->roleDescription = $role->description;
        $this->isSystemRole = $role->is_system_role;
        $this->isActive = $role->is_active;
        $this->rolePermissions = json_decode($role->permissions ?? '[]', true);
        
        $this->showEditRoleModal = true;
    }

    public function saveRole()
    {
        if ($this->roleId) {
            $this->rules['roleName'] = 'required|string|max:255|unique:roles,name,' . $this->roleId;
        }
        
        $this->validate([
            'roleName' => $this->rules['roleName'],
            'roleDisplayName' => $this->rules['roleDisplayName'],
            'roleDescription' => $this->rules['roleDescription'],
        ]);

        $roleData = [
            'name' => $this->roleName,
            'display_name' => $this->roleDisplayName,
            'description' => $this->roleDescription,
            'permissions' => json_encode($this->rolePermissions),
            'is_system_role' => $this->isSystemRole,
            'is_active' => $this->isActive,
        ];

        if ($this->roleId) {
            Role::findOrFail($this->roleId)->update($roleData);
            session()->flash('message', 'Role updated successfully!');
        } else {
            Role::create($roleData);
            session()->flash('message', 'Role created successfully!');
        }

        $this->closeModal();
        $this->resetRoleForm();
    }

    public function confirmDeleteRole($roleId)
    {
        $this->roleId = $roleId;
        $this->showDeleteRoleModal = true;
    }

    public function deleteRole()
    {
        $role = Role::findOrFail($this->roleId);
        
        if ($role->is_system_role) {
            session()->flash('error', 'Cannot delete system role!');
            return;
        }
        
        $role->delete();
        $this->showDeleteRoleModal = false;
        session()->flash('message', 'Role deleted successfully!');
    }

    // Permission Methods
    public function createPermission()
    {
        $this->resetPermissionForm();
        $this->showCreatePermissionModal = true;
    }

    public function editPermission($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $this->permissionId = $permission->id;
        $this->permissionName = $permission->name;
        $this->permissionDisplayName = $permission->display_name;
        $this->permissionDescription = $permission->description;
        $this->permissionCategory = $permission->category;
        $this->isSystemPermission = $permission->is_system_permission;
        
        $this->showEditPermissionModal = true;
    }

    public function savePermission()
    {
        if ($this->permissionId) {
            $this->rules['permissionName'] = 'required|string|max:255|unique:permissions,name,' . $this->permissionId;
        }
        
        $this->validate([
            'permissionName' => $this->rules['permissionName'],
            'permissionDisplayName' => $this->rules['permissionDisplayName'],
            'permissionDescription' => $this->rules['permissionDescription'],
            'permissionCategory' => $this->rules['permissionCategory'],
        ]);

        $permissionData = [
            'name' => $this->permissionName,
            'display_name' => $this->permissionDisplayName,
            'description' => $this->permissionDescription,
            'category' => $this->permissionCategory,
            'is_system_permission' => $this->isSystemPermission,
        ];

        if ($this->permissionId) {
            Permission::findOrFail($this->permissionId)->update($permissionData);
            session()->flash('message', 'Permission updated successfully!');
        } else {
            Permission::create($permissionData);
            session()->flash('message', 'Permission created successfully!');
        }

        $this->closeModal();
        $this->resetPermissionForm();
    }

    public function confirmDeletePermission($permissionId)
    {
        $this->permissionId = $permissionId;
        $this->showDeletePermissionModal = true;
    }

    public function deletePermission()
    {
        $permission = Permission::findOrFail($this->permissionId);
        
        if ($permission->is_system_permission) {
            session()->flash('error', 'Cannot delete system permission!');
            return;
        }
        
        $permission->delete();
        $this->showDeletePermissionModal = false;
        session()->flash('message', 'Permission deleted successfully!');
    }

    // Role Assignment Methods
    public function showAssignRole()
    {
        $this->resetAssignForm();
        $this->showAssignRoleModal = true;
    }

    public function assignRole()
    {
        $this->validate([
            'selectedUserId' => 'required|exists:users,id',
            'selectedRoleId' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        
        // Check if user already has this role
        if ($user->roles->contains($this->selectedRoleId)) {
            session()->flash('error', 'User already has this role!');
            return;
        }

        $user->roles()->attach($this->selectedRoleId, [
            'additional_permissions' => json_encode($this->additionalPermissions),
            'revoked_permissions' => json_encode($this->revokedPermissions),
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
        ]);

        $this->closeModal();
        session()->flash('message', 'Role assigned successfully!');
        $this->resetAssignForm();
    }

    public function revokeUserRole($userId, $roleId)
    {
        $user = User::findOrFail($userId);
        $user->roles()->detach($roleId);
        session()->flash('message', 'Role revoked successfully!');
    }

    public function toggleRoleStatus($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->update(['is_active' => !$role->is_active]);
        session()->flash('message', 'Role status updated successfully!');
    }

    public function closeModal()
    {
        $this->showCreateRoleModal = false;
        $this->showEditRoleModal = false;
        $this->showDeleteRoleModal = false;
        $this->showCreatePermissionModal = false;
        $this->showEditPermissionModal = false;
        $this->showDeletePermissionModal = false;
        $this->showAssignRoleModal = false;
        $this->resetErrorBag();
    }

    public function resetRoleForm()
    {
        $this->roleId = null;
        $this->roleName = '';
        $this->roleDisplayName = '';
        $this->roleDescription = '';
        $this->rolePermissions = [];
        $this->isSystemRole = false;
        $this->isActive = true;
    }

    public function resetPermissionForm()
    {
        $this->permissionId = null;
        $this->permissionName = '';
        $this->permissionDisplayName = '';
        $this->permissionDescription = '';
        $this->permissionCategory = '';
        $this->isSystemPermission = false;
    }

    public function resetAssignForm()
    {
        $this->selectedUserId = null;
        $this->selectedRoleId = null;
        $this->additionalPermissions = [];
        $this->revokedPermissions = [];
    }

    public function render()
    {
        $roles = Role::query()
            ->when($this->search && $this->activeTab === 'roles', function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('display_name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount('users')
            ->latest()
            ->paginate(15, ['*'], 'rolesPage');

        $permissions = Permission::query()
            ->when($this->search && $this->activeTab === 'permissions', function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('display_name', 'like', '%' . $this->search . '%')
                      ->orWhere('category', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(15, ['*'], 'permissionsPage');

        $userRoles = collect();
        if ($this->activeTab === 'assignments') {
            $userRoles = User::with('roles')
                ->when($this->search, function (Builder $query) {
                    $query->where(function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                })
                ->latest()
                ->paginate(15, ['*'], 'assignmentsPage');
        }

        $allPermissions = Permission::all()->groupBy('category');
        $allRoles = Role::where('is_active', true)->get();
        $allUsers = User::where('status', 'active')->get();

        return view('livewire.admin.roles-permissions', compact(
            'roles', 'permissions', 'userRoles', 'allPermissions', 'allRoles', 'allUsers'
        ));
    }
}