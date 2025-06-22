<div>
<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20" x-data="navbarData()">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Left Section -->
            <div class="flex items-center space-x-4">
                <!-- Sidebar Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-yellow-500 transition-colors duration-200">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                
                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-yellow-500 transition-colors duration-200 md:hidden">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                
                <!-- Breadcrumb / Page Title -->
                <div class="hidden md:flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">
                        @yield('page-title', 'Dashboard')
                    </h1>
                   


                </div>
            </div>
            
            <!-- Center Section - Search -->
            <div class="hidden md:flex flex-1 max-w-lg mx-8">
                <div class="relative w-full" x-data="{ searchOpen: false, searchQuery: '' }">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               x-model="searchQuery"
                               @focus="searchOpen = true"
                               @click.away="searchOpen = false"
                               placeholder="Search members, events, institutions..."
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm transition-colors duration-200">
                    </div>
                    
                    <!-- Search Dropdown -->
                    <div x-show="searchOpen && searchQuery.length > 2" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute z-50 mt-1 w-full bg-white shadow-lg max-h-96 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                        
                        <!-- Search Results -->
                        <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide bg-gray-50">
                            Quick Actions
                        </div>
                        <a href="{{ route('members.create') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-user-plus w-4 h-4 mr-3 text-gray-400"></i>
                            <span>Add New Member</span>
                        </a>
                        <a href="{{ route('events.create') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-calendar-plus w-4 h-4 mr-3 text-gray-400"></i>
                            <span>Create Event</span>
                        </a>
                        <a href="{{ route('budgets.create') }}" class="flex items-center px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-plus-circle w-4 h-4 mr-3 text-gray-400"></i>
                            <span>Submit Budget</span>
                        </a>
                        
                        <div class="border-t border-gray-100 mt-2 pt-2">
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wide bg-gray-50">
                                Recent Items
                            </div>
                            <!-- Dynamic search results would go here -->
                            <div class="px-4 py-2 text-sm text-gray-500">
                                Start typing to search...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                <!-- Quick Actions -->
                <div class="hidden lg:flex items-center space-x-2">
                    @can('manage-events')
                    <a href="{{ route('events.create') }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        Event
                    </a>
                    @endcan
                    
                    @can('manage-members')
                    <a href="{{ route('members.create') }}" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <i class="fas fa-user-plus w-4 h-4 mr-2"></i>
                        Member
                    </a>
                    @endcan
                </div>
                
                <!-- Notifications -->
                <div class="relative" x-data="{ notificationOpen: false }">
                    <button @click="notificationOpen = !notificationOpen" 
                            class="p-2 rounded-full text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200 relative">
                        <i class="fas fa-bell text-lg"></i>
                        @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notifications Dropdown -->
                    <div x-show="notificationOpen" 
                         @click.away="notificationOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        
                        <div class="py-1">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                                    <a href="{{ route('notifications.index') }}" class="text-xs text-yellow-600 hover:text-yellow-900">
                                        View all
                                    </a>
                                </div>
                            </div>
                            
                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->notifications->take(5) ?? [] as $notification)
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 {{ $notification->read_at ? 'opacity-75' : '' }}">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-bell text-yellow-600 text-sm"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $notification->data['message'] ?? 'You have a new notification' }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-gray-300 text-2xl mb-2"></i>
                                        <p class="text-sm">No notifications</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                @auth
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" 
                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <div class="w-8 h-8 bg-primary-yellow rounded-full flex items-center justify-center">
                            <span class="text-primary-black font-semibold text-sm">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </span>
                        </div>
                        <div class="hidden md:block text-left">
                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500 capitalize">{{ auth()->user()->role ?? 'Member' }}</div>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </button>
                    
                    <!-- User Dropdown -->
                    <div x-show="userMenuOpen" 
                         @click.away="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        
                        <div class="py-1">
                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary-yellow rounded-full flex items-center justify-center mr-3">
                                        <span class="text-primary-black font-bold">
                                            {{ substr(auth()->user()->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Menu Items -->
                            <a href="{{ route('profile.show') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user w-4 h-4 mr-3 text-gray-400"></i>
                                Your Profile
                            </a>
                            
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-cog w-4 h-4 mr-3 text-gray-400"></i>
                                Settings
                            </a>
                            
                            <a href="{{ route('certificates.my') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-certificate w-4 h-4 mr-3 text-gray-400"></i>
                                My Certificates
                            </a>
                            
                            @can('view-reports')
                            <a href="{{ route('reports.my-activity') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-chart-line w-4 h-4 mr-3 text-gray-400"></i>
                                Activity Report
                            </a>
                            @endcan
                            
                            <div class="border-t border-gray-100"></div>
                            
                            <a href="{{ route('help') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-question-circle w-4 h-4 mr-3 text-gray-400"></i>
                                Help & Support
                            </a>
                            
                            <div class="border-t border-gray-100"></div>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-left">
                                    <i class="fas fa-sign-out-alt w-4 h-4 mr-3 text-gray-400"></i>
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
    
    <!-- Mobile Search Bar -->
    <div class="md:hidden px-6 pb-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" 
                   placeholder="Search..."
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm">
        </div>
    </div>
</header>

<script>
function navbarData() {
    return {
        // Any navbar-specific JavaScript logic can go here
    }
}
</script>

</div>
