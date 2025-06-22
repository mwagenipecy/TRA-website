<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">All Members</h1>
            <p class="text-sm text-gray-600">Manage and view all organization members</p>
        </div>
        
        <div class="flex space-x-3">
            @if($showBulkActions)
                <div class="flex items-center space-x-2 mr-4">
                    <span class="text-sm text-gray-600">{{ count($selectedMembers) }} selected</span>
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
            
            <a href="{{ route('members.create') }}"  
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400">
                <i class="fas fa-plus mr-2"></i>
                Add Member
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $members->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Members</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Member::where('status', 'active')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Approval</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Member::where('status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-crown text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Leaders</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Member::whereIn('member_type', ['leader', 'supervisor'])->count() }}
                    </p>
                </div>
            </div>
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
                           placeholder="Search by name, email, or student ID..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
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
                    <option value="graduated">Graduated</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Member Type</label>
                <select wire:model.live="typeFilter" 
                        class="block w-full py-2 px-3 border border-gray-300 rounded-md text-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">All Types</option>
                    <option value="student">Student</option>
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
        </div>

        @if($search || $statusFilter || $typeFilter || $institutionFilter)
            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">Active filters:</span>
                    @if($search)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                            Search: {{ $search }}
                        </span>
                    @endif
                    @if($statusFilter)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                            Status: {{ ucfirst($statusFilter) }}
                        </span>
                    @endif
                    @if($typeFilter)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                            Type: {{ ucfirst($typeFilter) }}
                        </span>
                    @endif
                    @if($institutionFilter)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                            Institution: {{ $institutions->firstWhere('id', $institutionFilter)?->name }}
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

    <!-- Members Table -->
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
                                <span>Member</span>
                                <i class="{{ $this->getSortIcon('name') }}"></i>
                            </div>
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Institution
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Course
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" 
                            wire:click="sortBy('member_type')">
                            <div class="flex items-center space-x-1">
                                <span>Type</span>
                                <i class="{{ $this->getSortIcon('member_type') }}"></i>
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
                            wire:click="sortBy('joined_date')">
                            <div class="flex items-center space-x-1">
                                <span>Joined</span>
                                <i class="{{ $this->getSortIcon('joined_date') }}"></i>
                            </div>
                        </th>
                        
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50 {{ in_array($member->id, $selectedMembers ?? []) ? 'bg-yellow-50' : '' }}">
                            <td class="px-6 py-4">
                                <input type="checkbox" 
                                       wire:model.live="selectedMembers" 
                                       value="{{ $member->id }}"
                                       class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($member->user->profile_photo)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ Storage::url($member->user->profile_photo) }}" 
                                                 alt="{{ $member->user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-yellow-600">
                                                    {{ substr($member->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('members.edit', $member) }}" 
                                               class="hover:text-yellow-600">
                                                {{ $member->user->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $member->user->email }}</div>
                                        @if($member->student_id)
                                            <div class="text-xs text-gray-400">ID: {{ $member->student_id }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $member->institution->name }}</div>
                                <div class="text-xs text-gray-500">{{ $member->institution->type }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($member->course_of_study)
                                    <div class="text-sm text-gray-900">{{ $member->course_of_study }}</div>
                                    @if($member->year_of_study)
                                        <div class="text-sm text-gray-500">Year {{ $member->year_of_study }}</div>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">Not specified</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $member->member_type === 'student' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $member->member_type === 'leader' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $member->member_type === 'supervisor' ? 'bg-indigo-100 text-indigo-800' : '' }}">
                                    {{ ucfirst($member->member_type) }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $member->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $member->status === 'inactive' ? 'bg-gray-100 text-gray-800' : '' }}
                                    {{ $member->status === 'graduated' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $member->status === 'suspended' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $member->joined_date ? $member->joined_date->format('M d, Y') : 'Not set' }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('members.edit', $member) }}" 
                                       class="text-yellow-600 hover:text-yellow-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($member->status === 'pending')
                                        <button wire:click="approveMember({{ $member->id }})" 
                                                class="text-green-600 hover:text-green-900" 
                                                title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button wire:click="rejectMember({{ $member->id }})" 
                                                class="text-red-600 hover:text-red-900" 
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                    
                                    @if($member->status === 'active')
                                        <button wire:click="suspendMember({{ $member->id }})" 
                                                class="text-orange-600 hover:text-orange-900" 
                                                title="Suspend">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    @endif
                                    
                                    @if($member->status === 'suspended')
                                        <button wire:click="activateMember({{ $member->id }})" 
                                                class="text-blue-600 hover:text-blue-900" 
                                                title="Activate">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('members.edit', $member) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button wire:click="confirmDelete({{ $member->id }})" 
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
                                    <i class="fas fa-users text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No members found</p>
                                    <p class="text-sm">
                                        @if($search || $statusFilter || $typeFilter || $institutionFilter)
                                            Try adjusting your filters or 
                                            <button wire:click="clearFilters" class="text-yellow-600 hover:text-yellow-900">
                                                clear all filters
                                            </button>
                                        @else
                                            Get started by adding your first member
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
        @if($members->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $members->links() }}
            </div>

         @endif 


        </div>

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
                            <h3 class="text-lg font-medium text-gray-900">Remove Member</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to remove this member? This action cannot be undone and will remove all associated data.
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
                    <button wire:click="deleteMember" 
                            class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    @endif



</div>