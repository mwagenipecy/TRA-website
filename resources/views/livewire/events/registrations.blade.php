<div>
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Event Registrations</h1>
                <p class="mt-1 text-gray-600">Manage participant registrations and attendance</p>
            </div>
            
            <!-- Bulk Actions -->
            @if(!empty($selectedRegistrations))
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-600">{{ count($selectedRegistrations) }} selected</span>
                <button wire:click="openBulkModal" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Bulk Actions
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['approved']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-times text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['rejected']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-user-check text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Attended</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['attended']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Registrations</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 pl-10"
                           placeholder="Search by participant name, email, or event...">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="statusFilter" 
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="attended">Attended</option>
                    <option value="no_show">No Show</option>
                </select>
            </div>

            <!-- Event Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                <select wire:model.live="eventFilter" 
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ Str::limit($event->title, 30) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment</label>
                <select wire:model.live="paymentStatusFilter" 
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                    <option value="">All Payment Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
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

    <!-- Registrations Table -->
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Participant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Event
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registration Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Payment
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($registrations as $registration)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <input type="checkbox" 
                                       wire:model.live="selectedRegistrations" 
                                       value="{{ $registration->id }}"
                                       class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                            </td>
                            
                            <!-- Participant Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($registration->user->profile_photo)
                                            <img class="h-10 w-10 rounded-full object-cover" 
                                                 src="{{ Storage::url($registration->user->profile_photo) }}" 
                                                 alt="{{ $registration->user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <span class="text-yellow-600 font-medium text-sm">
                                                    {{ substr($registration->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $registration->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $registration->user->email }}</div>
                                        @if($registration->user->phone)
                                            <div class="text-xs text-gray-400">{{ $registration->user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Event Info -->
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $registration->event->title }}</div>
                                <div class="text-sm text-gray-500">{{ $registration->event->start_date->format('M d, Y g:i A') }}</div>
                                <div class="text-xs text-gray-400">{{ $registration->event->venue }}</div>
                            </td>

                            <!-- Registration Date -->
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $registration->registered_at->format('M d, Y') }}
                                <div class="text-xs text-gray-500">{{ $registration->registered_at->format('g:i A') }}</div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full w-fit
                                        @if($registration->status === 'approved') bg-green-100 text-green-800
                                        @elseif($registration->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($registration->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($registration->status === 'cancelled') bg-gray-100 text-gray-800
                                        @elseif($registration->status === 'attended') bg-blue-100 text-blue-800
                                        @else bg-orange-100 text-orange-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $registration->status)) }}
                                    </span>
                                    
                                    @if($registration->attended)
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800 w-fit">
                                            <i class="fas fa-check mr-1"></i>Attended
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Payment Status -->
                            <td class="px-6 py-4">
                                @if($registration->payment_required)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($registration->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($registration->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($registration->payment_status === 'failed') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800
                                        @endif">
                                        {{ ucfirst($registration->payment_status) }}
                                    </span>
                                    @if($registration->amount_paid)
                                        <div class="text-xs text-gray-500 mt-1">${{ number_format($registration->amount_paid, 2) }}</div>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-500">No payment required</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="showRegistrationDetails({{ $registration->id }})"
                                            class="text-yellow-600 hover:text-yellow-700 text-sm"
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if($registration->status === 'pending')
                                        @can('approve-registration', $registration)
                                        <button wire:click="approveRegistration({{ $registration->id }})"
                                                class="text-green-600 hover:text-green-700 text-sm"
                                                title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        
                                        <button wire:click="rejectRegistration({{ $registration->id }})"
                                                class="text-red-600 hover:text-red-700 text-sm"
                                                title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endcan
                                    @endif

                                    @if($registration->status === 'approved' && !$registration->attended)
                                        <button wire:click="markAsAttended({{ $registration->id }})"
                                                class="text-purple-600 hover:text-purple-700 text-sm"
                                                title="Mark as Attended">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <i class="fas fa-user-times text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No Registrations Found</h3>
                                <p class="text-gray-600">
                                    @if($search || $statusFilter || $eventFilter || $paymentStatusFilter)
                                        Try adjusting your filters to find more registrations.
                                    @else
                                        Event registrations will appear here when participants sign up.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $registrations->links() }}
        </div>
        @endif
    </div>

    <!-- Registration Details Modal -->
    @if($showRegistrationModal && $selectedRegistration)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" wire:click="closeRegistrationModal">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Registration Details</h2>
                            <p class="text-sm text-gray-600">{{ $selectedRegistration->event->title }}</p>
                        </div>
                        <button wire:click="closeRegistrationModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Registration Info -->
                    <div class="space-y-6">
                        <!-- Participant Details -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Participant Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="font-medium text-gray-700">Name:</span>
                                    <div>{{ $selectedRegistration->user->name }}</div>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Email:</span>
                                    <div>{{ $selectedRegistration->user->email }}</div>
                                </div>
                                @if($selectedRegistration->user->phone)
                                <div>
                                    <span class="font-medium text-gray-700">Phone:</span>
                                    <div>{{ $selectedRegistration->user->phone }}</div>
                                </div>
                                @endif
                                <div>
                                    <span class="font-medium text-gray-700">Member Type:</span>
                                    <div>{{ ucfirst($selectedRegistration->is_member ? 'Member' : 'Non-member') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Status -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Registration Status</h3>
                            <div class="flex items-center space-x-4 mb-3">
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($selectedRegistration->status === 'approved') bg-green-100 text-green-800
                                    @elseif($selectedRegistration->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($selectedRegistration->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $selectedRegistration->status)) }}
                                </span>
                                
                                @if($selectedRegistration->attended)
                                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                                        Attended
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-600">
                                <div>Registered: {{ $selectedRegistration->registered_at->format('M d, Y g:i A') }}</div>
                                @if($selectedRegistration->approved_at)
                                    <div>{{ ucfirst($selectedRegistration->status) }}: {{ $selectedRegistration->approved_at->format('M d, Y g:i A') }}</div>
                                @endif
                                @if($selectedRegistration->approvedBy)
                                    <div>By: {{ $selectedRegistration->approvedBy->name }}</div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Information -->
                        @if($selectedRegistration->payment_required)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Payment Information</h3>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-blue-900">Status:</span>
                                        <div>{{ ucfirst($selectedRegistration->payment_status) }}</div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-900">Amount:</span>
                                        <div>${{ number_format($selectedRegistration->amount_paid ?? $selectedRegistration->event->registration_fee, 2) }}</div>
                                    </div>
                                    @if($selectedRegistration->payment_reference)
                                    <div class="col-span-2">
                                        <span class="font-medium text-blue-900">Reference:</span>
                                        <div>{{ $selectedRegistration->payment_reference }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Additional Information -->
                        @if($selectedRegistration->additional_info)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Additional Information</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <pre class="whitespace-pre-wrap text-sm text-gray-700">{{ json_encode($selectedRegistration->additional_info, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                        @endif

                        <!-- Approval Notes -->
                        @if($selectedRegistration->approval_notes)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <p class="text-sm text-yellow-800">{{ $selectedRegistration->approval_notes }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Action Form -->
                        @if($selectedRegistration->status === 'pending')
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Take Action</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                                    <select wire:model.live="registrationAction" 
                                            class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                        <option value="">Select action</option>
                                        <option value="approve">Approve Registration</option>
                                        <option value="reject">Reject Registration</option>
                                    </select>
                                </div>

                                @if($registrationAction)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                    <textarea wire:model="registrationNotes" 
                                              rows="3"
                                              class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                              placeholder="Add any notes for this action..."></textarea>
                                </div>

                                <div class="flex space-x-3">
                                    <button wire:click="processRegistrationAction"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">
                                        {{ ucfirst($registrationAction) }} Registration
                                    </button>
                                    <button wire:click="closeRegistrationModal"
                                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                                        Cancel
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bulk Action Modal -->
    @if($showBulkModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" wire:click="closeBulkModal">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" wire:click.stop>
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Bulk Action</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Action</label>
                            <select wire:model="bulkAction" 
                                    class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Choose action</option>
                                <option value="approve">Approve Selected</option>
                                <option value="reject">Reject Selected</option>
                                <option value="mark_attended">Mark as Attended</option>
                            </select>
                        </div>

                        @if(in_array($bulkAction, ['approve', 'reject']))
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea wire:model="bulkApprovalNotes" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Add notes for this bulk action..."></textarea>
                        </div>
                        @endif

                        <div class="flex space-x-3 pt-4">
                            <button wire:click="processBulkAction"
                                    {{ !$bulkAction ? 'disabled' : '' }}
                                    class="bg-yellow-500 hover:bg-yellow-600 disabled:bg-gray-300 text-white px-4 py-2 rounded-lg font-medium">
                                Apply to {{ count($selectedRegistrations) }} Items
                            </button>
                            <button wire:click="closeBulkModal"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                                Cancel
                            </button>
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
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>
</div>
</div>
