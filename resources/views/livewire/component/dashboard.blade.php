<div>

<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-lg shadow-sm p-6 text-black">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Welcome to Vilabu vya Kodi</h2>
                <p class="text-black/80">Manage your tax club activities and engage with the community.</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-graduation-cap text-6xl text-black/20"></i>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Members -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Members</p>
                    <p class="text-2xl font-semibold text-gray-900">1,234</p>
                    <p class="text-xs text-green-600">
                        <i class="fas fa-arrow-up"></i> +12% from last month
                    </p>
                </div>
            </div>
        </div>

        <!-- Active Events -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Events</p>
                    <p class="text-2xl font-semibold text-gray-900">8</p>
                    <p class="text-xs text-blue-600">
                        <i class="fas fa-calendar"></i> 3 this week
                    </p>
                </div>
            </div>
        </div>

        <!-- Pending Budgets -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Budgets</p>
                    <p class="text-2xl font-semibold text-gray-900">5</p>
                    <p class="text-xs text-orange-600">
                        <i class="fas fa-clock"></i> Awaiting approval
                    </p>
                </div>
            </div>
        </div>

        <!-- Institutions -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-university text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Institutions</p>
                    <p class="text-2xl font-semibold text-gray-900">67</p>
                    <p class="text-xs text-purple-600">
                        <i class="fas fa-map-marker-alt"></i> Nationwide
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activities -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activities</h3>
                    <a href="{{ route('activities.index') }}" class="text-sm text-yellow-600 hover:text-yellow-900">
                        View all
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Activity Item -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-plus text-green-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium">John Doe</span> joined the tax club at University of Dar es Salaam
                            </p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">
                                New event <span class="font-medium">"Tax Awareness Workshop"</span> was created
                            </p>
                            <p class="text-xs text-gray-500">4 hours ago</p>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-yellow-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">
                                Budget proposal for <span class="font-medium">"Student Seminar 2025"</span> was approved
                            </p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>

                    <!-- Activity Item -->
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-certificate text-purple-600 text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">
                                <span class="font-medium">25 certificates</span> were issued for the Tax Compliance Workshop
                            </p>
                            <p class="text-xs text-gray-500">2 days ago</p>
                        </div>
                    </div>
                </div>
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
                <div class="space-y-4">
                    <!-- Event Item -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900">Tax Law Seminar</h4>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Tomorrow</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">University of Dar es Salaam</p>
                        <p class="text-xs text-gray-500">10:00 AM - 2:00 PM</p>
                        <div class="mt-2 flex items-center text-xs text-gray-500">
                            <i class="fas fa-users mr-1"></i>
                            <span>45 registered</span>
                        </div>
                    </div>

                    <!-- Event Item -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900">VAT Workshop</h4>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">June 25</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sokoine University</p>
                        <p class="text-xs text-gray-500">9:00 AM - 12:00 PM</p>
                        <div class="mt-2 flex items-center text-xs text-gray-500">
                            <i class="fas fa-users mr-1"></i>
                            <span>23 registered</span>
                        </div>
                    </div>

                    <!-- Event Item -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900">Digital Tax Filing</h4>
                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">June 28</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Mzumbe University</p>
                        <p class="text-xs text-gray-500">2:00 PM - 5:00 PM</p>
                        <div class="mt-2 flex items-center text-xs text-gray-500">
                            <i class="fas fa-users mr-1"></i>
                            <span>12 registered</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @can('manage-members')
                <a href="{{ route('members.create') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="p-3 bg-blue-100 rounded-full mr-4">
                        <i class="fas fa-user-plus text-blue-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Add Member</h4>
                        <p class="text-xs text-gray-500">Register new club member</p>
                    </div>
                </a>
                @endcan

                @can('manage-events')
                <a href="{{ route('events.create') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="p-3 bg-green-100 rounded-full mr-4">
                        <i class="fas fa-calendar-plus text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Create Event</h4>
                        <p class="text-xs text-gray-500">Schedule new activity</p>
                    </div>
                </a>
                @endcan

                @can('manage-budgets')
                <a href="{{ route('budgets.create') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="p-3 bg-yellow-100 rounded-full mr-4">
                        <i class="fas fa-plus-circle text-yellow-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Submit Budget</h4>
                        <p class="text-xs text-gray-500">Create budget proposal</p>
                    </div>
                </a>
                @endcan

                @can('view-reports')
                <a href="{{ route('reports.dashboard') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="p-3 bg-purple-100 rounded-full mr-4">
                        <i class="fas fa-chart-bar text-purple-600"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">View Reports</h4>
                        <p class="text-xs text-gray-500">Analytics & insights</p>
                    </div>
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Recent Approvals (TRA Officers) -->
    @can('approve-budgets')
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Pending Approvals</h3>
                <a href="{{ route('budgets.pending') }}" class="text-sm text-yellow-600 hover:text-yellow-900">
                    View all
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <!-- Approval Item -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Annual Budget 2025</h4>
                            <p class="text-xs text-gray-500">University of Dar es Salaam - Submitted 2 days ago</p>
                            <p class="text-xs text-gray-600 font-medium">TSH 2,500,000</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-md hover:bg-green-200">
                            Approve
                        </button>
                        <button class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                            Reject
                        </button>
                    </div>
                </div>

                <!-- Approval Item -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900">Workshop Expenses</h4>
                            <p class="text-xs text-gray-500">Sokoine University - Submitted 1 day ago</p>
                            <p class="text-xs text-gray-600 font-medium">TSH 450,000</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-md hover:bg-green-200">
                            Approve
                        </button>
                        <button class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>

@push('scripts')
<script>
    // Dashboard-specific JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh dashboard every 5 minutes
        setInterval(function() {
            // You can implement auto-refresh logic here
            console.log('Dashboard auto-refresh check');
        }, 300000);
    });
</script>
@endpush


</div>
