<div>
<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Institution Approvals</h1>
                <p class="mt-1 text-sm text-gray-600">Manage institution approval requests and status changes</p>
            </div>
            
            <!-- Bulk Actions -->
            @if(count($selectedInstitutions) > 0)
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600">{{ count($selectedInstitutions) }} selected</span>
                    <button wire:click="bulkApprove" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <i class="fas fa-check mr-2"></i>
                        Bulk Approve
                    </button>
                    <button wire:click="bulkReject" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-times mr-2"></i>
                        Bulk Reject
                    </button>
                </div>
            @endif
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Inactive</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['inactive'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-pause-circle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Suspended</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['suspended'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" 
                               type="text" 
                               id="search"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500" 
                               placeholder="Search institutions...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="statusFilter" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select wire:model.live="statusFilter" 
                            id="statusFilter"
                            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="all">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="typeFilter" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select wire:model.live="typeFilter" 
                            id="typeFilter"
                            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="all">All Types</option>
                        <option value="university">University</option>
                        <option value="college">College</option>
                        <option value="secondary_school">Secondary School</option>
                        <option value="primary_school">Primary School</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div>
                    <label for="perPage" class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
                    <select wire:model.live="perPage" 
                            id="perPage"
                            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Institutions Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            @if($institutions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" 
                                           wire:model.live="selectAll"
                                           class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('name')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Institution</span>
                                        @if($sortBy === 'name')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-yellow-500"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-300"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('type')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Type</span>
                                        @if($sortBy === 'type')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-yellow-500"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-300"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('status')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Status</span>
                                        @if($sortBy === 'status')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-yellow-500"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-300"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Created</span>
                                        @if($sortBy === 'created_at')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-yellow-500"></i>
                                        @else
                                            <i class="fas fa-sort text-gray-300"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($institutions as $institution)
                                <tr class="hover:bg-gray-50 {{ in_array($institution->id, $selectedInstitutions) ? 'bg-yellow-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" 
                                               wire:model.live="selectedInstitutions" 
                                               value="{{ $institution->id }}"
                                               class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($institution->logo_url)
                                                    <img class="h-10 w-10 rounded-lg object-cover border border-gray-200" 
                                                         src="{{ $institution->logo_url }}" 
                                                         alt="{{ $institution->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-lg bg-yellow-100 flex items-center justify-center border border-gray-200">
                                                        <i class="fas fa-university text-yellow-600"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('institutions.show', $institution) }}" class="hover:text-yellow-600">
                                                        {{ $institution->name }}
                                                    </a>
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $institution->code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $institution->type_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $institution->city }}</div>
                                        <div class="text-xs text-gray-500">{{ $institution->region }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass($institution->status) }}">
                                            {{ $institution->status_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $institution->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs">{{ $institution->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            @foreach($this->getActionButtons($institution) as $button)
                                                <button wire:click="{{ $button['action'] }}({{ $button['params'] }})" 
                                                        class="{{ $button['class'] }}" 
                                                        title="{{ $button['title'] }}">
                                                    <i class="{{ $button['icon'] }}"></i>
                                                </button>
                                            @endforeach
                                            <a href="{{ route('institutions.show', $institution) }}" 
                                               class="text-yellow-600 hover:text-yellow-900" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($institutions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $institutions->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <i class="fas fa-university text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No institutions found</h3>
                    <p class="text-gray-500 mb-6">
                        @if($search || $statusFilter !== 'all' || $typeFilter !== 'all')
                            Try adjusting your search criteria or filters.
                        @else
                            No institutions have been registered yet.
                        @endif
                    </p>
                    @if($search || $statusFilter !== 'all' || $typeFilter !== 'all')
                        <button wire:click="$set('search', ''); $set('statusFilter', 'all'); $set('typeFilter', 'all')" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

</div>
