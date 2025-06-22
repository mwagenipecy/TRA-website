<div>
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Event Calendar</h1>
                <p class="mt-1 text-gray-600">View and manage events in calendar format</p>
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

    <!-- Calendar Controls -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
            <!-- Navigation Controls -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <button wire:click="previousPeriod" 
                            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <button wire:click="today" 
                            class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Today
                    </button>
                    
                    <button wire:click="nextPeriod" 
                            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Current Period Display -->
                <div class="text-lg font-semibold text-gray-900">
                    @if($currentView === 'month')
                        {{ $currentDate->format('F Y') }}
                    @elseif($currentView === 'week')
                        Week of {{ $currentDate->startOfWeek(Carbon\Carbon::SUNDAY)->format('M d') }} - 
                        {{ $currentDate->endOfWeek(Carbon\Carbon::SATURDAY)->format('M d, Y') }}
                    @else
                        {{ $currentDate->format('F d, Y') }}
                    @endif
                </div>
            </div>

            <!-- View Controls -->
            <div class="flex items-center space-x-4">
                <!-- View Toggles -->
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button wire:click="changeView('month')" 
                            class="px-3 py-1 text-sm font-medium rounded-md {{ $currentView === 'month' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Month
                    </button>
                    <button wire:click="changeView('week')" 
                            class="px-3 py-1 text-sm font-medium rounded-md {{ $currentView === 'week' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Week
                    </button>
                    <button wire:click="changeView('day')" 
                            class="px-3 py-1 text-sm font-medium rounded-md {{ $currentView === 'day' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                        Day
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
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
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($currentView === 'month')
            <!-- Month View -->
            <div class="grid grid-cols-7">
                <!-- Day Headers -->
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="bg-gray-50 px-4 py-3 text-center text-sm font-medium text-gray-700 border-b border-gray-200">
                        {{ $day }}
                    </div>
                @endforeach

                <!-- Calendar Days -->
                @foreach($calendarDates as $date)
                    @php
                        $events = $this->getEventsForDate($date);
                        $isCurrentMonth = $date->month === $currentDate->month;
                        $isToday = $date->isToday();
                    @endphp
                    
                    <div class="min-h-[120px] border-b border-r border-gray-200 p-2 {{ !$isCurrentMonth ? 'bg-gray-50' : '' }} {{ $isToday ? 'bg-yellow-50' : '' }}">
                        <!-- Date Number -->
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium {{ !$isCurrentMonth ? 'text-gray-400' : ($isToday ? 'text-yellow-600' : 'text-gray-900') }}">
                                {{ $date->format('j') }}
                            </span>
                            @if($isToday)
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                            @endif
                        </div>

                        <!-- Events -->
                        <div class="space-y-1">
                            @foreach($events->take(3) as $event)
                                <div wire:click="showEvent({{ $event->id }})" 
                                     class="cursor-pointer text-xs p-1 rounded truncate
                                        @if($event->status === 'published') bg-green-100 text-green-800 hover:bg-green-200
                                        @elseif($event->status === 'draft') bg-gray-100 text-gray-800 hover:bg-gray-200
                                        @elseif($event->status === 'cancelled') bg-red-100 text-red-800 hover:bg-red-200
                                        @else bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                        @endif">
                                    {{ $event->title }}
                                </div>
                            @endforeach
                            
                            @if($events->count() > 3)
                                <div class="text-xs text-gray-500 font-medium">
                                    +{{ $events->count() - 3 }} more
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        @elseif($currentView === 'week')
            <!-- Week View -->
            <div class="grid grid-cols-8">
                <!-- Time Column Header -->
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200"></div>
                
                <!-- Day Headers -->
                @foreach($calendarDates as $date)
                    <div class="bg-gray-50 px-4 py-3 text-center border-b border-r border-gray-200">
                        <div class="text-sm font-medium text-gray-700">{{ $date->format('D') }}</div>
                        <div class="text-lg font-semibold {{ $date->isToday() ? 'text-yellow-600' : 'text-gray-900' }}">
                            {{ $date->format('j') }}
                        </div>
                    </div>
                @endforeach

                <!-- Time Slots (8 AM to 8 PM) -->
                @for($hour = 8; $hour <= 20; $hour++)
                    <!-- Time Label -->
                    <div class="px-4 py-4 text-sm text-gray-600 border-b border-gray-200 bg-gray-50">
                        {{ \Carbon\Carbon::createFromTime($hour, 0)->format('g A') }}
                    </div>
                    
                    <!-- Day Columns -->
                    @foreach($calendarDates as $date)
                        @php
                            $dayEvents = $this->getEventsForDate($date)->filter(function($event) use ($hour) {
                                $startHour = $event->start_date->hour;
                                $endHour = $event->end_date->hour;
                                return $hour >= $startHour && $hour <= $endHour;
                            });
                        @endphp
                        
                        <div class="min-h-[60px] border-b border-r border-gray-200 p-1 {{ $date->isToday() ? 'bg-yellow-50' : '' }}">
                            @foreach($dayEvents as $event)
                                <div wire:click="showEvent({{ $event->id }})" 
                                     class="cursor-pointer text-xs p-1 rounded mb-1 truncate
                                        @if($event->status === 'published') bg-green-100 text-green-800 hover:bg-green-200
                                        @elseif($event->status === 'draft') bg-gray-100 text-gray-800 hover:bg-gray-200
                                        @elseif($event->status === 'cancelled') bg-red-100 text-red-800 hover:bg-red-200
                                        @else bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                        @endif">
                                    {{ $event->title }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @endfor
            </div>

        @else
            <!-- Day View -->
            <div class="p-6">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $currentDate->format('l, F j, Y') }}</h2>
                </div>

                @php
                    $dayEvents = $this->getEventsForDate($currentDate);
                @endphp

                @if($dayEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($dayEvents->sortBy('start_date') as $event)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                                 wire:click="showEvent({{ $event->id }})">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $event->institution->name }}</p>
                                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                                            <span><i class="fas fa-clock mr-1"></i>{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</span>
                                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->venue }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($event->status === 'published') bg-green-100 text-green-800
                                            @elseif($event->status === 'draft') bg-gray-100 text-gray-800
                                            @elseif($event->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                        <div class="mt-1">
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 text-xs font-medium rounded-full">
                                                {{ ucfirst($event->type) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Scheduled</h3>
                        <p class="text-gray-600">There are no events scheduled for this day.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Event Details Modal -->
    @if($showEventModal && $selectedEvent)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" wire:click="closeEventModal">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">{{ $selectedEvent->title }}</h2>
                            <p class="text-sm text-gray-600">{{ $selectedEvent->institution->name }}</p>
                        </div>
                        <button wire:click="closeEventModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Event Details -->
                    <div class="space-y-4">
                        <!-- Status and Type -->
                        <div class="flex space-x-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($selectedEvent->status === 'published') bg-green-100 text-green-800
                                @elseif($selectedEvent->status === 'draft') bg-gray-100 text-gray-800
                                @elseif($selectedEvent->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($selectedEvent->status) }}
                            </span>
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 text-xs font-medium rounded-full">
                                {{ ucfirst($selectedEvent->type) }}
                            </span>
                        </div>

                        <!-- Date and Time -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Start:</span>
                                    <div>{{ $selectedEvent->start_date->format('M d, Y g:i A') }}</div>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">End:</span>
                                    <div>{{ $selectedEvent->end_date->format('M d, Y g:i A') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div>
                            <span class="font-medium text-gray-700">Location:</span>
                            <div class="text-gray-900">{{ $selectedEvent->venue }}</div>
                            @if($selectedEvent->address)
                                <div class="text-sm text-gray-600">{{ $selectedEvent->address }}</div>
                            @endif
                        </div>

                        <!-- Description -->
                        <div>
                            <span class="font-medium text-gray-700">Description:</span>
                            <div class="text-gray-900 mt-1">{{ $selectedEvent->description }}</div>
                        </div>

                        <!-- Registration Info -->
                        @if($selectedEvent->registration_start)
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h4 class="font-medium text-blue-900 mb-2">Registration Information</h4>
                                <div class="text-sm text-blue-800">
                                    <div>Period: {{ $selectedEvent->registration_start->format('M d') }} - {{ $selectedEvent->registration_end->format('M d, Y') }}</div>
                                    @if($selectedEvent->is_free)
                                        <div>Fee: Free</div>
                                    @else
                                        <div>Fee: ${{ number_format($selectedEvent->registration_fee, 2) }}</div>
                                    @endif
                                    @if($selectedEvent->max_participants)
                                        <div>Max Participants: {{ $selectedEvent->max_participants }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('events.show', $selectedEvent) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                View Details
                            </a>
                            @can('update', $selectedEvent)
                                <a href="{{ route('events.edit', $selectedEvent) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Edit Event
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading States -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40 items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-500"></div>
            <span class="text-gray-700">Loading calendar...</span>
        </div>
    </div>
</div>

</div>
