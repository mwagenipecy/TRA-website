<div>
<div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <div class="bg-black border-b border-yellow-500">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-yellow-400">Roles & Permissions</h1>
                    <p class="text-gray-400 mt-1">Manage system roles, permissions, and user assignments</p>
                </div>
                <div class="flex space-x-3">
                    @if ($activeTab === 'roles')
                        <button wire:click="createRole" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Role
                        </button>
                    @elseif ($activeTab === 'permissions')
                        <button wire:click="createPermission" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add Permission
                        </button>
                    @elseif ($activeTab === 'assignments')
                        <button wire:click="showAssignRole" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Assign Role
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-gray-800 border-b border-gray-700">
        <div class="px-6">
            <nav class="flex space-x-8">
                <button wire:click="setActiveTab('roles')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'roles' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-300' }}">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Roles
                </button>
                <button wire:click="setActiveTab('permissions')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'permissions' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-300' }}">
                    <i class="fas fa-key mr-2"></i>
                    Permissions
                </button>
                <button wire:click="setActiveTab('assignments')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ $activeTab === 'assignments' ? 'border-yellow-500 text-yellow-400' : 'border-transparent text-gray-400 hover:text-gray-300' }}">
                    <i class="fas fa-users-cog mr-2"></i>
                    User Assignments
                </button>
            </nav>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-gray-800 border-b border-gray-700 px-6 py-4">
        <div class="max-w-md">
            <input wire:model.live="search" 
                   type="text" 
                   placeholder="Search {{ $activeTab }}..." 
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session()->has('message'))
        <div class="bg-yellow-500 text-black px-6 py-3 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-500 text-white px-6 py-3 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Content -->
    <div class="px-6 py-6">
        @if ($activeTab === 'roles')
            <!-- Roles Table -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-black">
                            <tr>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Role</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Description</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Users</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Status</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Type</th>
                                <th class="px-6 py-4 text-center text-yellow-400 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse ($roles as $role)
                                <tr class="hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-white font-medium">{{ $role->display_name }}</div>
                                            <div class="text-gray-400 text-sm">{{ $role->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-300 text-sm">
                                            {{ Str::limit($role->description, 100) ?: 'No description' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-yellow-900 text-yellow-300 px-2 py-1 rounded-full text-xs font-medium">
                                            {{ $role->users_count }} users
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $role->is_active ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $role->is_system_role ? 'bg-blue-900 text-blue-300' : 'bg-gray-700 text-gray-300' }}">
                                            {{ $role->is_system_role ? 'System' : 'Custom' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button wire:click="editRole({{ $role->id }})" 
                                                    class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                                    title="Edit Role">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="toggleRoleStatus({{ $role->id }})" 
                                                    class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                                    title="Toggle Status">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                            @if (!$role->is_system_role)
                                                <button wire:click="confirmDeleteRole({{ $role->id }})" 
                                                        class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                                        title="Delete Role">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fas fa-shield-alt text-4xl mb-4"></i>
                                        <p class="text-lg">No roles found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $roles->links() }}</div>

        @elseif ($activeTab === 'permissions')
            <!-- Permissions Table -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-black">
                            <tr>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Permission</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Category</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Description</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Type</th>
                                <th class="px-6 py-4 text-center text-yellow-400 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse ($permissions as $permission)
                                <tr class="hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-white font-medium">{{ $permission->display_name }}</div>
                                            <div class="text-gray-400 text-sm">{{ $permission->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-yellow-900 text-yellow-300 px-2 py-1 rounded-full text-xs font-medium">
                                            {{ ucfirst($permission->category) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-gray-300 text-sm">
                                            {{ Str::limit($permission->description, 100) ?: 'No description' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $permission->is_system_permission ? 'bg-blue-900 text-blue-300' : 'bg-gray-700 text-gray-300' }}">
                                            {{ $permission->is_system_permission ? 'System' : 'Custom' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button wire:click="editPermission({{ $permission->id }})" 
                                                    class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                                    title="Edit Permission">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @if (!$permission->is_system_permission)
                                                <button wire:click="confirmDeletePermission({{ $permission->id }})" 
                                                        class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                                        title="Delete Permission">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fas fa-key text-4xl mb-4"></i>
                                        <p class="text-lg">No permissions found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $permissions->links() }}</div>

        @else
            <!-- User Role Assignments -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-black">
                            <tr>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">User</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Assigned Roles</th>
                                <th class="px-6 py-4 text-left text-yellow-400 font-medium">Status</th>
                                <th class="px-6 py-4 text-center text-yellow-400 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse ($userRoles as $user)
                                <tr class="hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-black font-bold mr-3">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-white font-medium">{{ $user->name }}</div>
                                                <div class="text-gray-400 text-sm">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($user->roles as $role)
                                                <div class="flex items-center bg-yellow-900 text-yellow-300 px-2 py-1 rounded text-xs">
                                                    {{ $role->display_name }}
                                                    @if (!$role->is_system_role)
                                                        <button wire:click="revokeUserRole({{ $user->id }}, {{ $role->id }})" 
                                                                class="ml-1 text-red-400 hover:text-red-300"
                                                                title="Revoke Role">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            @empty
                                                <span class="text-gray-400 text-sm">No roles assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button wire:click="$set('selectedUserId', {{ $user->id }}); showAssignRole()" 
                                                    class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                                    title="Assign Role">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fas fa-users-cog text-4xl mb-4"></i>
                                        <p class="text-lg">No user assignments found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $userRoles->links() }}</div>
        @endif
    </div>

    <!-- Create/Edit Role Modal -->
    @if ($showCreateRoleModal || $showEditRoleModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-yellow-400">
                        {{ $showCreateRoleModal ? 'Create New Role' : 'Edit Role' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="saveRole">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Role Name *</label>
                            <input wire:model="roleName" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('roleName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Display Name *</label>
                            <input wire:model="roleDisplayName" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('roleDisplayName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Description</label>
                            <textarea wire:model="roleDescription" rows="3"
                                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"></textarea>
                            @error('roleDescription') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="isActive" 
                                       class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                <span class="ml-2 text-gray-300 text-sm">Active</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Permissions</label>
                            <div class="max-h-64 overflow-y-auto bg-gray-700 rounded-lg p-4">
                                @foreach ($allPermissions as $category => $categoryPermissions)
                                    <div class="mb-4">
                                        <h4 class="text-yellow-400 font-medium mb-2">{{ ucfirst($category) }}</h4>
                                        <div class="grid grid-cols-1 gap-2">
                                            @foreach ($categoryPermissions as $permission)
                                                <label class="flex items-center">
                                                    <input type="checkbox" wire:model="rolePermissions" value="{{ $permission->name }}" 
                                                           class="rounded bg-gray-600 border-gray-500 text-yellow-500 focus:ring-yellow-500">
                                                    <span class="ml-2 text-gray-300 text-sm">{{ $permission->display_name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            {{ $showCreateRoleModal ? 'Create Role' : 'Update Role' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Create/Edit Permission Modal -->
    @if ($showCreatePermissionModal || $showEditPermissionModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-xl border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-yellow-400">
                        {{ $showCreatePermissionModal ? 'Create New Permission' : 'Edit Permission' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="savePermission">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Permission Name *</label>
                            <input wire:model="permissionName" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('permissionName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Display Name *</label>
                            <input wire:model="permissionDisplayName" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('permissionDisplayName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Category *</label>
                            <input wire:model="permissionCategory" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('permissionCategory') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Description</label>
                            <textarea wire:model="permissionDescription" rows="3"
                                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500"></textarea>
                            @error('permissionDescription') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="isSystemPermission" 
                                       class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                <span class="ml-2 text-gray-300 text-sm">System Permission</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            {{ $showCreatePermissionModal ? 'Create Permission' : 'Update Permission' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Assign Role Modal -->
    @if ($showAssignRoleModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-xl border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-yellow-400">Assign Role to User</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="assignRole">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Select User *</label>
                            <select wire:model="selectedUserId" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                <option value="">Choose a user...</option>
                                @foreach ($allUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('selectedUserId') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Select Role *</label>
                            <select wire:model="selectedRoleId" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                <option value="">Choose a role...</option>
                                @foreach ($allRoles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            @error('selectedRoleId') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Additional Permissions</label>
                            <div class="max-h-32 overflow-y-auto bg-gray-700 rounded-lg p-3">
                                @foreach ($allPermissions as $category => $categoryPermissions)
                                    @foreach ($categoryPermissions as $permission)
                                        <label class="flex items-center mb-1">
                                            <input type="checkbox" wire:model="additionalPermissions" value="{{ $permission->name }}" 
                                                   class="rounded bg-gray-600 border-gray-500 text-yellow-500 focus:ring-yellow-500">
                                            <span class="ml-2 text-gray-300 text-xs">{{ $permission->display_name }}</span>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            Assign Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Role Confirmation Modal -->
    @if ($showDeleteRoleModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md border border-gray-700">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Delete Role</h3>
                    <p class="text-gray-400 mb-6">Are you sure you want to delete this role? This action cannot be undone.</p>
                    
                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteRoleModal', false)" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button wire:click="deleteRole" 
                                class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Permission Confirmation Modal -->
    @if ($showDeletePermissionModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md border border-gray-700">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Delete Permission</h3>
                    <p class="text-gray-400 mb-6">Are you sure you want to delete this permission? This action cannot be undone.</p>
                    
                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeletePermissionModal', false)" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button wire:click="deletePermission" 
                                class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

</div>
