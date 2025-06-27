<div>



<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Institutions</h1>
            <p class="text-sm text-gray-600">Manage educational institutions and their tax club programs</p>
        </div>
        
            <div class="flex space-x-3">
                @if($showBulkActions)
                    <div class="flex items-center space-x-2 mr-4">
                        <span class="text-sm text-gray-600">{{ count($selectedInstitutions) }} selected</span>
                        <button wire:click="bulkApprove" 
                                class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-md hover:bg-green-200">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                        <button wire:click="bulkReject" 
                                class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                    </div>
                @endif
                
                    <button wire:click="exportData" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-download mr-2"></i>
                        Export
                    </button>
                
                <a href="{{ route('institutions.create') }}"  
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400">
                    <i class="fas fa-plus mr-2"></i>
                    Add Institution
                </a>
            </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Search by name, code, city..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select wire:model.live="type" 
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">All Types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="status" 
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Region Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                <select wire:model.live="region" 
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">All Regions</option>
                    @foreach($regions as $regionName)
                        <option value="{{ $regionName }}">{{ $regionName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($search || $type || $status || $region)
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Active filters:</span>
                    @if($search)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                            Search: {{ $search }}
                        </span>
                    @endif
                    @if($type)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                            Type: {{ $types[$type] }}
                        </span>
                    @endif
                    @if($status)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                            Status: {{ $statuses[$status] }}
                        </span>
                    @endif
                    @if($region)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                            Region: {{ $region }}
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

    <!-- Institutions Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" 
                                       wire:model.live="selectAll"
                                       class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                            wire:click="sortBy('name')">
                            <div class="flex items-center space-x-1">
                                <span>Institution</span>
                                <i class="{{ $this->getSortIcon('name') }}"></i>
                            </div>
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                            wire:click="sortBy('type')">
                            <div class="flex items-center space-x-1">
                                <span>Type</span>
                                <i class="{{ $this->getSortIcon('type') }}"></i>
                            </div>
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                            wire:click="sortBy('members_count')">
                            <div class="flex items-center space-x-1">
                                <span>Members</span>
                                <i class="{{ $this->getSortIcon('members_count') }}"></i>
                            </div>
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                            wire:click="sortBy('status')">
                            <div class="flex items-center space-x-1">
                                <span>Status</span>
                                <i class="{{ $this->getSortIcon('status') }}"></i>
                            </div>
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                            wire:click="sortBy('created_at')">
                            <div class="flex items-center space-x-1">
                                <span>Registered</span>
                                <i class="{{ $this->getSortIcon('created_at') }}"></i>
                            </div>
                        </th>
                        
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($institutions as $institution)
                        <tr class="hover:bg-gray-50 {{ in_array($institution->id, $selectedInstitutions) ? 'bg-yellow-50' : '' }}">

                        <td class="px-6 py-4">
                                    <input type="checkbox" 
                                           wire:model.live="selectedInstitutions" 
                                           value="{{ $institution->id }}"
                                           class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($institution->logo_url)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ $institution->logo_url }}" 
                                                 alt="{{ $institution->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <i class="fas fa-university text-yellow-600"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('institutions.show', $institution) }}" 
                                               class="hover:text-yellow-600">
                                                {{ $institution->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $institution->code }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $institution->type === 'university' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $institution->type === 'college' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $institution->type === 'institute' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $institution->type === 'school' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ $institution->type_display }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $institution->city }}</div>
                                <div class="text-xs text-gray-500">{{ $institution->region }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-users text-gray-400 mr-2"></i>
                                    <span class="font-medium">{{ $institution->active_members_count }}</span>
                                    <span class="text-gray-500 ml-1">/ {{ $institution->members_count }}</span>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $institution->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $institution->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $institution->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $institution->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $institution->status_display }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $institution->created_at->format('M d, Y') }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('institutions.show', $institution) }}" 
                                       class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                        @if($institution->status === 'pending')
                                            <button wire:click="approveInstitution({{ $institution->id }})" 
                                                    class="text-green-600 hover:text-green-900" 
                                                    title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button wire:click="rejectInstitution({{ $institution->id }})" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        
                                        @if($institution->status === 'active')
                                            <button wire:click="suspendInstitution({{ $institution->id }})" 
                                                    class="text-orange-600 hover:text-orange-900" 
                                                    title="Suspend">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        @endif
                                        
                                        @if($institution->status === 'suspended')
                                            <button wire:click="activateInstitution({{ $institution->id }})" 
                                                    class="text-blue-600 hover:text-blue-900" 
                                                    title="Activate">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('institutions.edit', $institution) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                            <button wire:click="confirmDelete({{ $institution->id }})" 
                                                    class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                       
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-university text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No institutions found</p>
                                    <p class="text-sm">
                                        @if($search || $type || $status || $region)
                                            Try adjusting your filters or 
                                            <button wire:click="clearFilters" class="text-yellow-600 hover:text-yellow-900">
                                                clear all filters
                                            </button>
                                        @else
                                            Get started by adding your first institution
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($institutions->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $institutions->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletion)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-medium text-gray-900">Delete Institution</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this institution? This action cannot be undone and will remove all associated data.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex space-x-3 justify-end">
                    <button wire:click="$set('confirmingDeletion', false)" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="deleteInstitution" 
                            class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>




</div>
