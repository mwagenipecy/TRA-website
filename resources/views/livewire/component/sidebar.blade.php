<div>
<aside class="sidebar bg-primary-black text-white fixed left-0 top-0 h-full z-30 overflow-y-auto"
       :class="{ 'sidebar-collapsed': !sidebarOpen, 'open': mobileMenuOpen }"
       x-data="sidebarData()">
    
    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-700">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-primary-yellow rounded-full flex items-center justify-center mr-3">
                <span class="text-primary-black font-bold text-lg">VK</span>
            </div>
            <div class="flex flex-col">
                <span class="text-primary-yellow font-bold text-lg">Vilabu vya Kodi</span>
                <span class="text-gray-400 text-xs">Tax Clubs Management</span>
            </div>
        </div>
    </div>
    
    <!-- User Info Section -->
    @auth
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-primary-yellow rounded-full flex items-center justify-center mr-3">
                <span class="text-primary-black font-semibold text-sm">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </span>
            </div>
            <div class="flex flex-col">
                <span class="text-white font-medium text-sm">{{ auth()->user()->name }}</span>
                <span class="text-gray-400 text-xs capitalize">{{ auth()->user()->role ?? 'Member' }}</span>
            </div>
        </div>
    </div>
    @endauth
    
    <!-- Navigation Menu -->
    <nav class="py-4">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" 
                   class="menu-item flex items-center px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <!-- Institution Management (TRA Officers & Leaders) -->
            <li x-data="{ open: {{ request()->routeIs('institutions.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="menu-item w-full flex items-center justify-between px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('institutions.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-university w-5 h-5 mr-3"></i>
                        <span>Institutions</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul class="submenu pl-4" :class="{ 'open': open }">
                    <li>
                        <a href="{{ route('institutions.index') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('institutions.index') ? 'active' : '' }}">
                            <i class="fas fa-list w-4 h-4 mr-3"></i>
                            <span>All Institutions</span>
                        </a>
                    </li>
                 
                    <li>
                        <a href="{{ route('institutions.create') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('institutions.create') ? 'active' : '' }}">
                            <i class="fas fa-plus w-4 h-4 mr-3"></i>
                            <span>Add Institution</span> 
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('institutions.approval') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('institutions.approval') ? 'active' : '' }}">
                            <i class="fas fa-check-circle w-4 h-4 mr-3"></i>
                            <span>Pending Approvals</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Member Management -->
            <li x-data="{ open: {{ request()->routeIs('members.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="menu-item w-full flex items-center justify-between px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-users w-5 h-5 mr-3"></i>
                        <span>Members</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul class="submenu pl-4" :class="{ 'open': open }">

                <li>
                        <a href="{{ route('members.index') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('members.index') ? 'active' : '' }}">
                            <i class="fas fa-list w-4 h-4 mr-3"></i>
                            <span>All Members</span>
                        </a>
                    </li>
                  
                    <li>
                        <a href="{{ route('members.pending') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('members.pending') ? 'active' : '' }}">
                            <i class="fas fa-clock w-4 h-4 mr-3"></i>
                            <span>Pending Approval</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('members.leaders') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('members.leaders') ? 'active' : '' }}">
                            <i class="fas fa-crown w-4 h-4 mr-3"></i>
                            <span>Leaders & Supervisors</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Events Management -->
            <li x-data="{ open: {{ request()->routeIs('events.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="menu-item w-full flex items-center justify-between px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                        <span>Events</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul class="submenu pl-4" :class="{ 'open': open }">

                <li>
                        <a href="{{ route('events.index') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('events.index') ? 'active' : '' }}">
                            <i class="fas fa-list w-4 h-4 mr-3"></i>
                            <span>All Events</span>
                        </a>
                    </li>
                
                    <li>
                        <a href="{{ route('events.create') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('events.create') ? 'active' : '' }}">
                            <i class="fas fa-plus w-4 h-4 mr-3"></i>
                            <span>Create Event</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.calendar') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('events.calendar') ? 'active' : '' }}">
                            <i class="fas fa-calendar w-4 h-4 mr-3"></i>
                            <span>Event Calendar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('events.registrations') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('events.registrations') ? 'active' : '' }}">
                            <i class="fas fa-user-check w-4 h-4 mr-3"></i>
                            <span>Registrations</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Budget Management -->
            <li x-data="{ open: {{ request()->routeIs('budgets.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="menu-item w-full flex items-center justify-between px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-wallet w-5 h-5 mr-3"></i>
                        <span>Budgets</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul class="submenu pl-4" :class="{ 'open': open }">
                    <li>
                        <a href="{{ route('budgets.index') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('budgets.index') ? 'active' : '' }}">
                            <i class="fas fa-list w-4 h-4 mr-3"></i>
                            <span>All Budgets</span>
                        </a>
                    </li>
               
                    <li>
                        <a href="{{ route('budgets.create') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('budgets.create') ? 'active' : '' }}">
                            <i class="fas fa-plus w-4 h-4 mr-3"></i>
                            <span>Create Budget</span>
                        </a>
                    </li>
             
                    <li>
                        <a href="{{ route('budgets.pending') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('budgets.pending') ? 'active' : '' }}">
                            <i class="fas fa-clock w-4 h-4 mr-3"></i>
                            <span>Pending Approval</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('budgets.yearly') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('budgets.yearly') ? 'active' : '' }}">
                            <i class="fas fa-calendar-year w-4 h-4 mr-3"></i>
                            <span>Yearly Plans</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Reports & Analytics -->
            <li x-data="{ open: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="menu-item w-full flex items-center justify-between px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                        <span>Reports</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul class="submenu pl-4" :class="{ 'open': open }">
                    <li>
                        <a href="{{ route('reports.dashboard') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('reports.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line w-4 h-4 mr-3"></i>
                            <span>Analytics Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.members') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('reports.members') ? 'active' : '' }}">
                            <i class="fas fa-users w-4 h-4 mr-3"></i>
                            <span>Member Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.events') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('reports.events') ? 'active' : '' }}">
                            <i class="fas fa-calendar w-4 h-4 mr-3"></i>
                            <span>Event Reports</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.financial') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                            <i class="fas fa-dollar-sign w-4 h-4 mr-3"></i>
                            <span>Financial Reports</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Certificates -->
            <li>
                <a href="{{ route('certificates.index') }}" 
                   class="menu-item flex items-center px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('certificates.*') ? 'active' : '' }}">
                    <i class="fas fa-certificate w-5 h-5 mr-3"></i>
                    <span>Certificates</span>
                </a>
            </li>
            
            <!-- Notifications -->
            <li>
                <a href="{{ route('notifications.index') }}" 
                   class="menu-item flex items-center px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="fas fa-bell w-5 h-5 mr-3"></i>
                    <span>Notifications</span>
                    @if(auth()->user() && auth()->user()->unreadNotifications->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </li>
            
            <!-- System Administration (TRA Officers only) -->
            <li class="mt-6">
                <div class="px-6 py-2">
                    <span class="text-gray-500 text-xs font-semibold uppercase tracking-wide">System Administration</span>
                </div>
            </li>

            <li x-data="{ open: {{ request()->routeIs('admin.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="menu-item w-full flex items-center justify-between px-6 py-3 text-gray-300 hover:text-white {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-cogs w-5 h-5 mr-3"></i>
                        <span>System Settings</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                </button>


                <ul class="submenu pl-4" :class="{ 'open': open }">
                    <li>
                        <a href="{{ url('users') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('users.users') ? 'active' : '' }}">
                            <i class="fas fa-user-cog w-4 h-4 mr-3"></i>
                            <span>User Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('roles') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('admin.roles') ? 'active' : '' }}">
                            <i class="fas fa-shield-alt w-4 h-4 mr-3"></i>
                            <span>Roles & Permissions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('system-settings') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                            <i class="fas fa-sliders-h w-4 h-4 mr-3"></i>
                            <span>System Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('system-logs') }}" 
                           class="menu-item flex items-center px-6 py-2 text-gray-400 hover:text-white text-sm {{ request()->routeIs('admin.audit') ? 'active' : '' }}">
                            <i class="fas fa-history w-4 h-4 mr-3"></i>
                            <span>Audit Logs</span>
                        </a>
                    </li>
                </ul>


            </li>
        </ul>
    </nav>
    
    <!-- Bottom Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-700">
        <div class="flex items-center justify-between">
            <div class="text-xs text-gray-500">
                <span>Version 1.0</span>
            </div>
            <a href=" " class="text-gray-400 hover:text-primary-yellow">
                <i class="fas fa-question-circle"></i>
            </a>
        </div>
    </div>
</aside>


</div>
