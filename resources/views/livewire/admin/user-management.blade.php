<div>
<div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <div class="bg-black border-b border-yellow-500">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-yellow-400">User Management</h1>
                    <p class="text-gray-400 mt-1">Manage system users, roles, and permissions</p>
                </div>
                <button wire:click="createUser" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Add New User
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 border-b border-gray-700 px-6 py-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Search users..." 
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
            </div>
            <div>
                <select wire:model.live="selectedRole" 
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    <option value="">All Roles</option>
                    <option value="student">Student</option>
                    <option value="leader">Leader</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="tra_officer">TRA Officer</option>
                </select>
            </div>
            <div>
                <select wire:model.live="selectedStatus" 
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="pending">Pending</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
            <div>
                <button wire:click="$refresh" 
                        class="w-full bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="bg-yellow-500 text-black px-6 py-3 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Users Table -->
    <div class="px-6 py-6">
        <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black">
                        <tr>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">User</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Role</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Status</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Last Login</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Roles</th>
                            <th class="px-6 py-4 text-center text-yellow-400 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-black font-bold mr-3">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-white font-medium">{{ $user->name }}</div>
                                            <div class="text-gray-400 text-sm">{{ $user->email }}</div>
                                            @if ($user->phone)
                                                <div class="text-gray-500 text-xs">{{ $user->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @switch($user->role)
                                            @case('tra_officer')
                                                bg-yellow-900 text-yellow-300
                                                @break
                                            @case('supervisor')
                                                bg-blue-900 text-blue-300
                                                @break
                                            @case('leader')
                                                bg-purple-900 text-purple-300
                                                @break
                                            @default
                                                bg-gray-700 text-gray-300
                                        @endswitch">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @switch($user->status)
                                            @case('active')
                                                bg-green-900 text-green-300
                                                @break
                                            @case('pending')
                                                bg-yellow-900 text-yellow-300
                                                @break
                                            @case('suspended')
                                                bg-red-900 text-red-300
                                                @break
                                            @default
                                                bg-gray-700 text-gray-300
                                        @endswitch">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-sm">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($user->roles as $role)
                                            <span class="px-2 py-1 bg-yellow-900 text-yellow-300 rounded text-xs">
                                                {{ $role->display_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button wire:click="editUser({{ $user->id }})" 
                                                class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                                title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="toggleUserStatus({{ $user->id }})" 
                                                class="text-blue-400 hover:text-blue-300 transition-colors duration-200"
                                                title="Toggle Status">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $user->id }})" 
                                                class="text-red-400 hover:text-red-300 transition-colors duration-200"
                                                title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg">No users found</p>
                                    <p class="text-sm">Try adjusting your search criteria</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if ($showCreateModal || $showEditModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-yellow-400">
                        {{ $showCreateModal ? 'Create New User' : 'Edit User' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="saveUser">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Name *</label>
                            <input wire:model="name" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Email *</label>
                            <input wire:model="email" type="email" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Phone</label>
                            <input wire:model="phone" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">National ID</label>
                            <input wire:model="national_id" type="text" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('national_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Date of Birth</label>
                            <input wire:model="date_of_birth" type="date" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('date_of_birth') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Gender</label>
                            <select wire:model="gender" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('gender') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Role *</label>
                            <select wire:model="role" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                <option value="student">Student</option>
                                <option value="leader">Leader</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="tra_officer">TRA Officer</option>
                            </select>
                            @error('role') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Status *</label>
                            <select wire:model="status" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            @error('status') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">
                                Password {{ $showEditModal ? '(leave blank to keep current)' : '*' }}
                            </label>
                            <input wire:model="password" type="password" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('password') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Confirm Password</label>
                            <input wire:model="password_confirmation" type="password" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-gray-300 text-sm font-medium mb-2">Additional Roles</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($roles as $role)
                                <label class="flex items-center">
                                    <input type="checkbox" wire:model="selectedRoles" value="{{ $role->id }}" 
                                           class="rounded bg-gray-700 border-gray-600 text-yellow-500 focus:ring-yellow-500">
                                    <span class="ml-2 text-gray-300 text-sm">{{ $role->display_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            {{ $showCreateModal ? 'Create User' : 'Update User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md border border-gray-700">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Delete User</h3>
                    <p class="text-gray-400 mb-6">Are you sure you want to delete this user? This action cannot be undone.</p>
                    
                    <div class="flex justify-center space-x-3">
                        <button wire:click="$set('showDeleteModal', false)" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button wire:click="deleteUser" 
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
