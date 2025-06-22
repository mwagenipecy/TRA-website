<div>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Notifications</h1>
        <div class="flex space-x-4">
            @if(auth()->user()->unreadNotifications->count() > 0)
            <button wire:click="markAllAsRead" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-semibold transition duration-200">
                <i class="fas fa-check-double mr-2"></i>Mark All Read
            </button>
            @endif
        </div>
    </div>

    {{-- Notification Filters --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex space-x-4">
            <button wire:click="$set('filter', 'all')" 
                    class="px-4 py-2 rounded-lg font-medium transition duration-200 
                    {{ $filter === 'all' ? 'bg-yellow-500 text-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                All Notifications
            </button>
            <button wire:click="$set('filter', 'unread')" 
                    class="px-4 py-2 rounded-lg font-medium transition duration-200 
                    {{ $filter === 'unread' ? 'bg-yellow-500 text-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Unread ({{ auth()->user()->unreadNotifications->count() }})
            </button>
            <button wire:click="$set('filter', 'read')" 
                    class="px-4 py-2 rounded-lg font-medium transition duration-200 
                    {{ $filter === 'read' ? 'bg-yellow-500 text-black' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                Read
            </button>
        </div>
    </div>

    {{-- Success Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    {{-- Notifications List --}}
    <div class="space-y-4 mb-6">
        @forelse($notifications as $notification)
        <div class="bg-white rounded-lg shadow-md overflow-hidden 
                    {{ !$notification->read_at ? 'border-l-4 border-yellow-500' : '' }}">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            {{-- Notification Icon --}}
                            @php
                                $iconClass = 'fas fa-bell';
                                $iconColor = 'text-blue-500';
                                
                                if(isset($notification->data['type'])) {
                                    switch($notification->data['type']) {
                                        case 'budget':
                                            $iconClass = 'fas fa-wallet';
                                            $iconColor = 'text-yellow-500';
                                            break;
                                        case 'event':
                                            $iconClass = 'fas fa-calendar';
                                            $iconColor = 'text-green-500';
                                            break;
                                        case 'certificate':
                                            $iconClass = 'fas fa-certificate';
                                            $iconColor = 'text-purple-500';
                                            break;
                                        case 'member':
                                            $iconClass = 'fas fa-users';
                                            $iconColor = 'text-blue-500';
                                            break;
                                    }
                                }
                            @endphp
                            
                            <div class="w-10 h-10 {{ $iconColor }} bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                <i class="{{ $iconClass }}"></i>
                            </div>
                            
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                    @if(!$notification->read_at)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                                        New
                                    </span>
                                    @endif
                                </h3>
                                <p class="text-gray-600 text-sm mt-1">
                                    {{ $notification->data['message'] ?? 'You have a new notification' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                            
                            <div class="flex space-x-3">
                                @if(!$notification->read_at)
                                <button wire:click="markAsRead('{{ $notification->id }}')" 
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-check mr-1"></i>Mark as Read
                                </button>
                                @endif
                                
                                {{-- Action Button --}}
                                @if(isset($notification->data['action_url']))
                                <a href="{{ $notification->data['action_url'] }}" 
                                   class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                    <i class="fas fa-external-link-alt mr-1"></i>View
                                </a>
                                @endif
                                
                                <button wire:click="deleteNotification('{{ $notification->id }}')" 
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Additional Notification Data --}}
                @if(isset($notification->data['details']) && is_array($notification->data['details']))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        @foreach($notification->data['details'] as $key => $value)
                        <div>
                            <span class="font-medium text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span class="text-gray-900 ml-2">{{ $value }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-bell-slash text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications found</h3>
            <p class="text-gray-500">
                @if($filter === 'unread')
                    You have no unread notifications.
                @elseif($filter === 'read')
                    You have no read notifications.
                @else
                    You don't have any notifications yet.
                @endif
            </p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
    <div class="bg-white rounded-lg shadow-md p-6">
        {{ $notifications->links() }}
    </div>
    @endif
</div>

</div>
