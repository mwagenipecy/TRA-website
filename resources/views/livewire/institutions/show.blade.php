<div>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-start justify-between">
        <div class="flex items-start space-x-4">
            <!-- Institution Logo -->
            <div class="flex-shrink-0">
                @if($institution->logo_url)
                    <img class="h-20 w-20 rounded-lg object-cover border border-gray-200" 
                         src="{{ $institution->logo_url }}" 
                         alt="{{ $institution->name }}">
                @else
                    <div class="h-20 w-20 rounded-lg bg-yellow-100 flex items-center justify-center border border-gray-200">
                        <i class="fas fa-university text-yellow-600 text-2xl"></i>
                    </div>
                @endif
            </div>
            
            <!-- Institution Info -->
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $institution->name }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeClass() }}">
                        {{ $institution->status_display }}
                    </span>
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span><i class="fas fa-code mr-1"></i>{{ $institution->code }}</span>
                    <span><i class="fas fa-building mr-1"></i>{{ $institution->type_display }}</span>
                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $institution->city }}, {{ $institution->region }}</span>
                    @if($institution->established_date)
                        <span><i class="fas fa-calendar mr-1"></i>Est. {{ $institution->established_date->year }}</span>
                    @endif
                </div>
                @if($institution->description)
                    <p class="mt-2 text-gray-700 max-w-2xl">{{ $institution->description }}</p>
                @endif
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('institutions.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Institutions
            </a>
            
            @can('manage-institution', $institution)
                <a href="{{ route('institutions.edit', $institution) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
            @endcan
            
            @foreach($this->getActionButtons() as $button)
                <button wire:click="{{ $button['action'] }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md {{ $button['class'] }}">
                    <i class="{{ $button['icon'] }} mr-2"></i>
                    {{ $button['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Members Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_members'] }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $stats['pending_members'] }} pending approval
                    </p>
                </div>
            </div>
        </div>

        <!-- Events Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Events</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_events'] }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $stats['upcoming_events'] }} upcoming
                    </p>
                </div>
            </div>
        </div>

        <!-- Budgets Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Budgets</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_budgets'] }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $stats['pending_budgets'] }} pending
                    </p>
                </div>
            </div>
        </div>

        <!-- Budget Amount Stats -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Budget ({{ date('Y') }})</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        TSH {{ number_format($stats['total_budget_amount'] / 1000000, 1) }}M
                    </p>
                    <p class="text-xs text-gray-500">
                        Approved amount
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    @if($institution->phone || $institution->email || $institution->website || $institution->contact_persons)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- General Contact -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">General Contact</h4>
                    <div class="space-y-2 text-sm">
                        @if($institution->phone)
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-phone w-4 h-4 mr-2"></i>
                                <a href="tel:{{ $institution->phone }}" class="hover:text-yellow-600">{{ $institution->phone }}</a>
                            </div>
                        @endif
                        @if($institution->email)
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-envelope w-4 h-4 mr-2"></i>
                                <a href="mailto:{{ $institution->email }}" class="hover:text-yellow-600">{{ $institution->email }}</a>
                            </div>
                        @endif
                        @if($institution->website)
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-globe w-4 h-4 mr-2"></i>
                                <a href="{{ $institution->website }}" target="_blank" class="hover:text-yellow-600">{{ $institution->website }}</a>
                            </div>
                        @endif
                        <div class="flex items-start text-gray-600">
                            <i class="fas fa-map-marker-alt w-4 h-4 mr-2 mt-0.5"></i>
                            <div>
                                <div>{{ $institution->address }}</div>
                                <div>{{ $institution->city }}, {{ $institution->region }}</div>
                                @if($institution->postal_code)
                                    <div>{{ $institution->postal_code }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Persons -->
                @if($institution->contact_persons)
                    @foreach($institution->contact_persons as $contact)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $contact['name'] }}</h4>
                            <div class="space-y-1 text-sm text-gray-600">
                                <div class="font-medium">{{ $contact['title'] }}</div>
                                @if(isset($contact['email']))
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope w-4 h-4 mr-2"></i>
                                        <a href="mailto:{{ $contact['email'] }}" class="hover:text-yellow-600">{{ $contact['email'] }}</a>
                                    </div>
                                @endif
                                @if(isset($contact['phone']))
                                    <div class="flex items-center">
                                        <i class="fas fa-phone w-4 h-4 mr-2"></i>
                                        <a href="tel:{{ $contact['phone'] }}" class="hover:text-yellow-600">{{ $contact['phone'] }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6">
                <button wire:click="$set('activeTab', 'overview')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Overview
                </button>
                <button wire:click="$set('activeTab', 'members')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'members' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Members ({{ $stats['total_members'] }})
                </button>
                <button wire:click="$set('activeTab', 'events')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'events' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Events ({{ $stats['total_events'] }})
                </button>
                <button wire:click="$set('activeTab', 'budgets')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'budgets' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Budgets ({{ $stats['total_budgets'] }})
                </button>
                <button wire:click="$set('activeTab', 'activities')" 
                        class="py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'activities' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Activity Log
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            @if($activeTab === 'overview')
                <!-- Overview Tab -->
                <div class="space-y-6">
                    <!-- Institution Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-base font-medium text-gray-900 mb-3">Institution Details</h4>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Status:</dt>
                                    <dd class="text-gray-900">{{ $institution->status_display }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Type:</dt>
                                    <dd class="text-gray-900">{{ $institution->type_display }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Code:</dt>
                                    <dd class="text-gray-900 font-mono">{{ $institution->code }}</dd>
                                </div>
                                @if($institution->established_date)
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600">Established:</dt>
                                        <dd class="text-gray-900">{{ $institution->established_date?->format('Y') }}</dd>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Registered:</dt>
                                    <dd class="text-gray-900">{{ $institution->created_at?->format('M d, Y') }}</dd>
                                </div>
                                @if($institution->approved_at)
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600">Approved:</dt>
                                        <dd class="text-gray-900">{{ $institution->approved_at?->format('M d, Y') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-base font-medium text-gray-900 mb-3">Key Personnel</h4>
                            <dl class="space-y-2 text-sm">
                                @if($institution->creator)
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600">Created by:</dt>
                                        <dd class="text-gray-900">{{ $institution->creator->name }}</dd>
                                    </div>
                                @endif
                                @if($institution->approver)
                                    <div class="flex justify-between">
                                        <dt class="font-medium text-gray-600">Approved by:</dt>
                                        <dd class="text-gray-900">{{ $institution->approver->name }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

            @elseif($activeTab === 'members')
                <!-- Members Tab -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-base font-medium text-gray-900">Institution Members</h4>
                        @can('manage-members')
                            <a href="{{ route('members.create') }}?institution={{ $institution->id }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400">
                                <i class="fas fa-plus mr-2"></i>
                                Add Member
                            </a>
                        @endcan
                    </div>

                    @if($members->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($members as $member)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8">
                                                        <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                                            <span class="text-xs font-medium text-yellow-600">
                                                                {{ $member->user->initials }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $member->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $member->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $member->member_type === 'leader' ? 'bg-purple-100 text-purple-800' : '' }}
                                                    {{ $member->member_type === 'supervisor' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $member->member_type === 'student' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                    {{ $member->member_type_display }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div>{{ $member->course_of_study }}</div>
                                                @if($member->year_of_study)
                                                    <div class="text-xs text-gray-500">{{ $member->academic_year_display }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getMemberStatusBadgeClass($member->status) }}">
                                                    {{ $member->status_display }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $member->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    @can('manage-members')
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
                                                    @endcan
                                                    <a href="{{ route('members.show', $member) }}" 
                                                       class="text-yellow-600 hover:text-yellow-900">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($members->hasPages())
                            <div class="mt-4">
                                {{ $members->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                            <p class="text-lg font-medium text-gray-500">No members found</p>
                            <p class="text-sm text-gray-400">Members will appear here once they join the tax club</p>
                        </div>
                    @endif
                </div>

            @elseif($activeTab === 'events')
                <!-- Events Tab -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-base font-medium text-gray-900">Institution Events</h4>
                        @can('manage-events')
                            <a href="{{ route('events.create') }}?institution={{ $institution->id }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400">
                                <i class="fas fa-plus mr-2"></i>
                                Create Event
                            </a>
                        @endcan
                    </div>

                    @if($recentEvents->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentEvents as $event)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h5 class="text-base font-medium text-gray-900">
                                                    <a href="{{ route('events.show', $event) }}" class="hover:text-yellow-600">
                                                        {{ $event->title }}
                                                    </a>
                                                </h5>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getEventStatusBadgeClass($event->status) }}">
                                                    {{ $event->status_display }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($event->description, 150) }}</p>
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span><i class="fas fa-calendar mr-1"></i>{{ $event->start_date->format('M d, Y') }}</span>
                                                <span><i class="fas fa-clock mr-1"></i>{{ $event->start_date->format('H:i') }}</span>
                                                <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->venue }}</span>
                                                @if($event->max_participants)
                                                    <span><i class="fas fa-users mr-1"></i>{{ $event->registration_count }}/{{ $event->max_participants }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('events.show', $event) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($recentEvents->hasPages())
                            <div class="mt-4">
                                {{ $recentEvents->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar text-gray-300 text-4xl mb-4"></i>
                            <p class="text-lg font-medium text-gray-500">No events found</p>
                            <p class="text-sm text-gray-400">Events organized by this institution will appear here</p>
                        </div>
                    @endif
                </div>

            @elseif($activeTab === 'budgets')
                <!-- Budgets Tab -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-base font-medium text-gray-900">Budget Proposals</h4>
                        @can('manage-budgets')
                            <a href="{{ route('budgets.create') }}?institution={{ $institution->id }}" 
                               class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400">
                                <i class="fas fa-plus mr-2"></i>
                                Submit Budget
                            </a>
                        @endcan
                    </div>

                    @if($recentBudgets->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentBudgets as $budget)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h5 class="text-base font-medium text-gray-900">
                                                    <a href="{{ route('budgets.show', $budget) }}" class="hover:text-yellow-600">
                                                        {{ $budget->title }}
                                                    </a>
                                                </h5>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getBudgetStatusBadgeClass($budget->status) }}">
                                                    {{ $budget->status_display }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">{{ $budget->description }}</p>
                                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                                <span><i class="fas fa-money-bill mr-1"></i>TSH {{ number_format($budget->total_amount) }}</span>
                                                <span><i class="fas fa-calendar mr-1"></i>FY {{ $budget->financial_year }}</span>
                                                <span><i class="fas fa-tag mr-1"></i>{{ $budget->type_display }}</span>
                                                <span><i class="fas fa-clock mr-1"></i>{{ $budget->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('budgets.show', $budget) }}" 
                                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($recentBudgets->hasPages())
                            <div class="mt-4">
                                {{ $recentBudgets->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-wallet text-gray-300 text-4xl mb-4"></i>
                            <p class="text-lg font-medium text-gray-500">No budgets found</p>
                            <p class="text-sm text-gray-400">Budget proposals from this institution will appear here</p>
                        </div>
                    @endif
                </div>

            @elseif($activeTab === 'activities')
                <!-- Activities Tab -->
                <div>
                    <h4 class="text-base font-medium text-gray-900 mb-4">Recent Activities</h4>
                    
                    @if($recentActivities->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentActivities as $activity)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clock text-yellow-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @if($activity->user)
                                                <span class="text-xs text-gray-500">by {{ $activity->user->name }}</span>
                                            @endif
                                            <span class="text-xs text-gray-500">{{ $activity->performed_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($recentActivities->hasPages())
                            <div class="mt-4">
                                {{ $recentActivities->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-history text-gray-300 text-4xl mb-4"></i>
                            <p class="text-lg font-medium text-gray-500">No activities found</p>
                            <p class="text-sm text-gray-400">Institution activities will appear here</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

</div>
