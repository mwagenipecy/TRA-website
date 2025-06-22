<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Leaders & Supervisors</h1>
            <p class="mt-1 text-sm text-gray-600">Manage organization leaders and supervisors</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('members.create') }}" 
               class="bg-primary-yellow hover:bg-yellow-400 text-black px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add Leader/Supervisor
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-crown text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Leaders</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Member::where('member_type', 'leader')->where('status', 'active')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Supervisors</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Member::where('member_type', 'supervisor')->where('status', 'active')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Leadership</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $leaders->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by name or email..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Type</label>
                <select wire:model.live="typeFilter" 
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">All Roles</option>
                    <option value="leader">Leader</option>
                    <option value="supervisor">Supervisor</option>
                </select>
            </div>

            <!-- Institution Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                <select wire:model.live="institutionFilter" 
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="statusFilter" 
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>

        @if($search || $typeFilter || $institutionFilter || $statusFilter)
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Active filters:</span>
                    @if($search)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                            Search: {{ $search }}
                        </span>
                    @endif
                    @if($typeFilter)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                            Role: {{ ucfirst($typeFilter) }}
                        </span>
                    @endif
                    @if($institutionFilter)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                            Institution: {{ $institutions->firstWhere('id', $institutionFilter)?->name }}
                        </span>
                    @endif
                    @if($statusFilter)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                            Status: {{ ucfirst($statusFilter) }}
                        </span>
                    @endif
                </div>
                <button wire:click="clearFilters" 
                        class="text-sm text-gray-500 hover:text-gray-700">
                    Clear all filters
                </button>
            </div>
        @endif
    </div>

    <!-- Leaders Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leader/Supervisor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Since</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsibilities</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($leaders as $leader)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12">
                                        @if($leader->user->profile_photo)
                                            <img class="h-12 w-12 rounded-full object-cover" 
                                                 src="{{ Storage::url($leader->user->profile_photo) }}" 
                                                 alt="{{ $leader->user->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <span class="text-lg font-medium text-yellow-700">
                                                    {{ substr($leader->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('members.edit', $leader) }}" 
                                               class="hover:text-yellow-600">
                                                {{ $leader->user->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $leader->user->email }}</div>
                                        @if($leader->user->phone)
                                            <div class="text-xs text-gray-400">{{ $leader->user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $leader->institution->name }}</div>
                                <div class="text-sm text-gray-500">{{ $leader->institution->type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full {{ $leader->member_type_badge }}">
                                    <i class="fas {{ $leader->member_type === 'leader' ? 'fa-crown' : 'fa-user-tie' }} mr-2"></i>
                                    {{ ucfirst($leader->member_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $leader->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $leader->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $leader->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $leader->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($leader->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $leader->joined_date ? $leader->joined_date->format('M d, Y') : 'Not set' }}
                                @if($leader->joined_date)
                                    <div class="text-xs text-gray-400">{{ $leader->joined_date->diffForHumans() }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @if($leader->member_type === 'leader')
                                        <div class="space-y-1">
                                            <div class="flex items-center text-xs text-gray-600">
                                                <i class="fas fa-users w-4 h-4 mr-2 text-yellow-600"></i>
                                                Member Management
                                            </div>
                                            <div class="flex items-center text-xs text-gray-600">
                                                <i class="fas fa-calendar w-4 h-4 mr-2 text-yellow-600"></i>
                                                Event Planning
                                            </div>
                                            <div class="flex items-center text-xs text-gray-600">
                                                <i class="fas fa-chart-line w-4 h-4 mr-2 text-yellow-600"></i>
                                                Progress Tracking
                                            </div>
                                        </div>
                                    @else
                                        <div class="space-y-1">
                                            <div class="flex items-center text-xs text-gray-600">
                                                <i class="fas fa-eye w-4 h-4 mr-2 text-indigo-600"></i>
                                                Oversight & Guidance
                                            </div>
                                            <div class="flex items-center text-xs text-gray-600">
                                                <i class="fas fa-clipboard-check w-4 h-4 mr-2 text-indigo-600"></i>
                                                Quality Assurance
                                            </div>
                                            <div class="flex items-center text-xs text-gray-600">
                                                <i class="fas fa-graduation-cap w-4 h-4 mr-2 text-indigo-600"></i>
                                                Mentoring
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('members.edit', $leader) }}" 
                                       class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button wire:click="$dispatch('member-details', { id: {{ $leader->id }} })"
                                            class="text-gray-600 hover:text-gray-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" 
                                                class="text-gray-400 hover:text-gray-600" title="More Actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             x-transition
                                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-200">
                                            <button wire:click="$dispatch('issue-certificate', { id: {{ $leader->id }} })"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <i class="fas fa-certificate mr-2 text-yellow-600"></i>
                                                Issue Certificate
                                            </button>
                                            <button wire:click="$dispatch('view-performance', { id: {{ $leader->id }} })"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                                                View Performance
                                            </button>
                                            <button wire:click="$dispatch('view-activities', { id: {{ $leader->id }} })"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                <i class="fas fa-history mr-2 text-gray-600"></i>
                                                Activity Log
                                            </button>
                                            @if($leader->status === 'active')
                                                <div class="border-t border-gray-100"></div>
                                                <button wire:click="$dispatch('suspend-member', { id: {{ $leader->id }} })"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                    <i class="fas fa-pause mr-2"></i>
                                                    Suspend
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-crown text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900 mb-2">No leaders or supervisors found</p>
                                    <p class="text-gray-600 mb-4">Try adjusting your search criteria or add new leadership roles.</p>
                                    <a href="{{ route('members.create') }}" 
                                       class="bg-primary-yellow hover:bg-yellow-400 text-black px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        Add First Leader
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($leaders->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $leaders->links() }}
            </div>
        @endif
    </div>
</div>