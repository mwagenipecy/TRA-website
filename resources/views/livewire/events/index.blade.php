<div>
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Events Management</h1>
                <p class="mt-1 text-gray-600">Manage and organize institutional events</p>
            </div>
            @can('create-event')
            <a href="{{ route('events.create') }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg inline-flex items-center font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Create Event
            </a>
            @endcan
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Events</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 pl-10"
                           placeholder="Search by title, description, or venue...">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="statusFilter" 
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="completed">Completed</option>
                    <option value="postponed">Postponed</option>
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select wire:model.live="typeFilter" 
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">All Types</option>
                    <option value="workshop">Workshop</option>
                    <option value="seminar">Seminar</option>
                    <option value="training">Training</option>
                    <option value="conference">Conference</option>
                    <option value="meeting">Meeting</option>
                    <option value="competition">Competition</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <!-- Institution Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Institution</label>
                <select wire:model.live="institutionFilter" 
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Clear Filters -->
        <div class="mt-4 flex justify-end">
            <button wire:click="clearFilters" 
                    class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                <i class="fas fa-times mr-1"></i>
                Clear Filters
            </button>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Event Image/Banner -->
                @if($event->banner_image)
                    <img src="{{ Storage::url($event->banner_image) }}" 
                         alt="{{ $event->title }}" 
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-4xl"></i>
                    </div>
                @endif

                <div class="p-6">
                    <!-- Event Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $event->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $event->institution->name }}</p>
                        </div>
                        
                        <!-- Status Badge -->
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($event->status === 'published') bg-green-100 text-green-800
                            @elseif($event->status === 'draft') bg-gray-100 text-gray-800
                            @elseif($event->status === 'cancelled') bg-red-100 text-red-800
                            @elseif($event->status === 'completed') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>

                    <!-- Event Type -->
                    <div class="flex items-center mb-3">
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 text-xs font-medium rounded-full">
                            {{ ucfirst($event->type) }}
                        </span>
                    </div>

                    <!-- Event Details -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar w-4 h-4 mr-2"></i>
                            {{ $event->start_date->format('M d, Y') }}
                            @if($event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d'))
                                - {{ $event->end_date->format('M d, Y') }}
                            @endif
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-clock w-4 h-4 mr-2"></i>
                            {{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt w-4 h-4 mr-2"></i>
                            {{ $event->venue }}
                        </div>
                        @if($event->max_participants)
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-users w-4 h-4 mr-2"></i>
                            Max {{ $event->max_participants }} participants
                        </div>
                        @endif
                    </div>

                    <!-- Event Description -->
                    <p class="text-sm text-gray-700 mb-4 line-clamp-3">
                        {{ Str::limit($event->description, 120) }}
                    </p>

                    <!-- Registration Info -->
                    @if($event->registration_start && $event->registration_end)
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <div class="text-xs text-gray-600 mb-1">Registration Period</div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $event->registration_start->format('M d') }} - {{ $event->registration_end->format('M d, Y') }}
                        </div>
                        @if(!$event->is_free)
                        <div class="text-sm text-yellow-600 font-medium">
                            Fee: ${{ number_format($event->registration_fee, 2) }}
                        </div>
                        @else
                        <div class="text-sm text-green-600 font-medium">Free Event</div>
                        @endif
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <a href="{{ route('events.show', $event) }}" 
                               class="text-yellow-600 hover:text-yellow-700 text-sm font-medium">
                                View Details
                            </a>
                            @can('update', $event)
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('events.edit', $event) }}" 
                               class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                Edit
                            </a>
                            @endcan
                        </div>

                        <div class="flex items-center space-x-1">
                            @can('update', $event)
                            <button wire:click="duplicateEvent({{ $event->id }})"
                                    class="text-gray-400 hover:text-gray-600 p-1"
                                    title="Duplicate Event">
                                <i class="fas fa-copy"></i>
                            </button>
                            @endcan
                            
                            @can('delete', $event)
                            <button wire:click="deleteEvent({{ $event->id }})"
                                    wire:confirm="Are you sure you want to delete this event?"
                                    class="text-red-400 hover:text-red-600 p-1"
                                    title="Delete Event">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="lg:col-span-3 xl:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Found</h3>
                    <p class="text-gray-600 mb-6">
                        @if($search || $statusFilter || $typeFilter || $institutionFilter)
                            Try adjusting your filters to find more events.
                        @else
                            Get started by creating your first event.
                        @endif
                    </p>
                    @can('create-event')
                        @if(!$search && !$statusFilter && !$typeFilter && !$institutionFilter)
                        <a href="{{ route('events.create') }}" 
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg inline-flex items-center font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Create Your First Event
                        </a>
                        @else
                        <button wire:click="clearFilters" 
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Clear Filters
                        </button>
                        @endif
                    @endcan
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        {{ $events->links() }}
    </div>
    @endif

    <!-- Loading States -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-500"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>
</div>

</div>
