<div class="space-y-6" wire:poll.{{ $refreshInterval }}ms="refreshData">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg shadow-sm p-6 text-black">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">
                    Welcome back, {{ $user->name }}!
                </h2>
                <p class="text-black/80">
                    {{ $this->getUserRoleDisplay() }} - {{ $this->getUserInstitution() }}
                </p>
                <p class="text-xs text-black/60 mt-2">
                    Last updated: {{ $lastUpdated->format('M d, Y H:i:s') }}
                </p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-tachometer-alt text-6xl text-black/20"></i>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @if($user->isTraOfficer())
            <!-- TRA Officer Stats -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Members</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_members'] ?? 0) }}</p>
                        @if(isset($stats['member_growth']) && $stats['member_growth'] != 0)
                            <p class="text-xs {{ $stats['member_growth'] > 0 ? 'text-green-600' : 'text-red-600' }}">
                                <i class="fas fa-arrow-{{ $stats['member_growth'] > 0 ? 'up' : 'down' }}"></i> 
                                {{ abs($stats['member_growth']) }}% from last month
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-university text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Institutions</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_institutions'] ?? 0) }}</p>
                        <p class="text-xs text-blue-600">
                            <i class="fas fa-clock"></i> {{ $stats['pending_institutions'] ?? 0 }} pending
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Events</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active_events'] ?? 0) }}</p>
                        <p class="text-xs text-purple-600">
                            <i class="fas fa-calendar"></i> Nationwide
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Budgets</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['pending_budgets'] ?? 0) }}</p>
                        <p class="text-xs text-orange-600">
                            <i class="fas fa-clock"></i> Awaiting review
                        </p>
                    </div>
                </div>
            </div>

        @elseif($user->isLeader())
            <!-- Leader/Supervisor Stats -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Club Members</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['institution_members'] ?? 0) }}</p>
                        <p class="text-xs text-blue-600">
                            <i class="fas fa-clock"></i> {{ $stats['pending_members'] ?? 0 }} pending
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-calendar-alt text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['institution_events'] ?? 0) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-calendar"></i> This month
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending Budgets</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['pending_budgets'] ?? 0) }}</p>
                        <p class="text-xs text-yellow-600">
                            <i class="fas fa-clock"></i> Under review
                        </p>
                    </div>
                </div>
            </div>

        @else
            <!-- Student Stats -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Events Attended</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['events_attended'] ?? 0) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-trophy"></i> Great participation!
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-calendar-plus text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Registered Events</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['events_registered'] ?? 0) }}</p>
                        <p class="text-xs text-blue-600">
                            <i class="fas fa-calendar"></i> Active registrations
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['upcoming_events'] ?? 0) }}</p>
                        <p class="text-xs text-purple-600">
                            <i class="fas fa-calendar-alt"></i> Don't miss out!
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
                    <div class="flex items-center space-x-2">
                        <button wire:click="refreshData" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Activity Filters -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <select wire:model.live="activityFilter" class="text-sm border-gray-300 rounded-md">
                        @foreach($this->getActivityTypeOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    
                    <select wire:model.live="dateRange" class="text-sm border-gray-300 rounded-md">
                        @foreach($this->getDateRangeOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" wire:model.live="showOnlyMyActivities" class="mr-2">
                        My activities only
                    </label>
                </div>
            </div>
            
            <div class="p-6">
                @if(count($recentActivities) > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 {{ $this->getActivityIconColor($activity['type']) }} rounded-full flex items-center justify-center">
                                        <i class="{{ $this->getActivityIcon($activity['type']) }} text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                                    <div class="flex items-center space-x-2 mt-1">
                                        @if(isset($activity['user']['name']))
                                            <span class="text-xs text-gray-500">by {{ $activity['user']['name'] }}</span>
                                        @endif
                                        @if(isset($activity['institution']['name']))
                                            <span class="text-xs text-gray-500">• {{ $activity['institution']['name'] }}</span>
                                        @endif
                                        <span class="text-xs text-gray-500">• {{ \Carbon\Carbon::parse($activity['performed_at'])->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-history text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">No recent activities found</p>
                        <p class="text-sm text-gray-400">Activities will appear here as they happen</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Upcoming Events</h3>
                    <a href="{{ route('events.index') }}" class="text-sm text-yellow-600 hover:text-yellow-900">
                        View all
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if(count($upcomingEvents) > 0)
                    <div class="space-y-4">
                        @foreach($upcomingEvents as $event)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $event['title'] }}</h4>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        {{ \Carbon\Carbon::parse($event['start_date'])->format('M d') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mb-2">{{ $event['venue'] }}</p>
                                <p class="text-xs text-gray-500 mb-2">{{ $event['institution'] }}</p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-users mr-1"></i>
                                        <span>{{ $event['registrations_count'] }} registered</span>
                                    </div>
                                    @if($user->isStudent())
                                        <button wire:click="registerForEvent({{ $event['id'] }})" 
                                                class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-200">
                                            Register
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar text-gray-300 text-3xl mb-3"></i>
                        <p class="text-gray-500">No upcoming events</p>
                        <p class="text-sm text-gray-400">Check back later for new events</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- TRA Officer Specific Sections -->
    @if($user->isTraOfficer())
        <!-- Pending Approvals -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Pending Approvals</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('budgets.pending') }}" class="text-sm text-yellow-600 hover:text-yellow-900">
                            View all budgets
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('institutions.approval') }}" class="text-sm text-yellow-600 hover:text-yellow-900">
                            View all institutions
                        </a>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Pending Budgets -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Budget Proposals</h4>
                        @if(count($pendingApprovals['budgets'] ?? []) > 0)
                            <div class="space-y-3">
                                @foreach($pendingApprovals['budgets'] as $budget)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="text-sm font-medium text-gray-900">{{ $budget['title'] }}</h5>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($budget['created_at'])->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-600 mb-2">{{ $budget['institution'] }}</p>
                                        <p class="text-sm font-semibold text-gray-900 mb-3">TSH {{ number_format($budget['amount'], 2) }}</p>
                                        <div class="flex space-x-2">
                                            <button wire:click="quickApproveBudget({{ $budget['id'] }})" 
                                                    class="flex-1 px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">
                                                <i class="fas fa-check mr-1"></i> Approve
                                            </button>
                                            <button wire:click="quickRejectBudget({{ $budget['id'] }})" 
                                                    class="flex-1 px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">
                                                <i class="fas fa-times mr-1"></i> Reject
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No pending budget approvals</p>
                        @endif
                    </div>

                    <!-- Pending Institutions -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">New Institutions</h4>
                        @if(count($pendingApprovals['institutions'] ?? []) > 0)
                            <div class="space-y-3">
                                @foreach($pendingApprovals['institutions'] as $institution)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="text-sm font-medium text-gray-900">{{ $institution['name'] }}</h5>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($institution['created_at'])->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-600 mb-1">{{ ucfirst($institution['type']) }}</p>
                                        <p class="text-xs text-gray-600 mb-3">{{ $institution['city'] }}</p>
                                        <a href="{{ route('institutions.show', $institution['id']) }}" 
                                           class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                            <i class="fas fa-eye mr-1"></i> Review
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No pending institution approvals</p>
                        @endif
                    </div>

                    <!-- Pending Members -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Member Applications</h4>
                        @if(count($pendingApprovals['members'] ?? []) > 0)
                            <div class="space-y-3">
                                @foreach($pendingApprovals['members'] as $member)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="text-sm font-medium text-gray-900">{{ $member['name'] }}</h5>
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($member['created_at'])->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-600 mb-3">{{ $member['institution'] }}</p>
                                        <a href="{{ route('members.show', $member['id']) }}" 
                                           class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                            <i class="fas fa-eye mr-1"></i> Review
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No pending member approvals</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics -->
        @if(count($charts) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Institutions by Region Chart -->
                @if(isset($charts['institutions_by_region']))
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Institutions by Region</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach($charts['institutions_by_region'] as $region)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700">{{ $region['region'] }}</span>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" 
                                                     style="width: {{ ($region['count'] / max(array_column($charts['institutions_by_region'], 'count'))) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $region['count'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Monthly Registrations Chart -->
                @if(isset($charts['monthly_registrations']))
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Monthly Member Registrations</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @php
                                    $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                    $maxCount = max(array_column($charts['monthly_registrations'], 'count'));
                                @endphp
                                @foreach($charts['monthly_registrations'] as $data)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700">{{ $months[$data['month'] - 1] }}</span>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-400 h-2 rounded-full" 
                                                     style="width: {{ $maxCount > 0 ? ($data['count'] / $maxCount) * 100 : 0 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $data['count'] }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endif

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($this->getQuickActions() as $action)
                    <a href="{{ route($action['route']) }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="p-3 bg-{{ $action['color'] }}-100 rounded-full mr-4">
                            <i class="{{ $action['icon'] }} text-{{ $action['color'] }}-600"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $action['title'] }}</h4>
                            <p class="text-xs text-gray-500">{{ $action['description'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@script
<script>
    // Helper methods for activity icons and colors
    window.getActivityIcon = function(type) {
        const icons = {
            'user_registered': 'fas fa-user-plus',
            'event_created': 'fas fa-calendar-plus',
            'event_registered': 'fas fa-calendar-check',
            'budget_submitted': 'fas fa-file-invoice-dollar',
            'budget_approved': 'fas fa-check-circle',
            'budget_rejected': 'fas fa-times-circle',
            'member_approved': 'fas fa-user-check',
            'institution_approved': 'fas fa-university'
        };
        return icons[type] || 'fas fa-info-circle';
    };

    window.getActivityIconColor = function(type) {
        const colors = {
            'user_registered': 'bg-blue-100 text-blue-600',
            'event_created': 'bg-green-100 text-green-600',
            'event_registered': 'bg-purple-100 text-purple-600',
            'budget_submitted': 'bg-yellow-100 text-yellow-600',
            'budget_approved': 'bg-green-100 text-green-600',
            'budget_rejected': 'bg-red-100 text-red-600',
            'member_approved': 'bg-blue-100 text-blue-600',
            'institution_approved': 'bg-indigo-100 text-indigo-600'
        };
        return colors[type] || 'bg-gray-100 text-gray-600';
    };

    // Auto-refresh notification
    $wire.on('dashboard-refreshed', () => {
        // You can add a toast notification here
        console.log('Dashboard refreshed at', new Date().toLocaleTimeString());
    });
</script>
@endscript