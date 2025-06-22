<div class="max-w-6xl mx-auto space-y-6">
    <!-- Event Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($event->banner_image)
            <div class="h-64 bg-cover bg-center relative" 
                 style="background-image: url('{{ Storage::url($event->banner_image) }}')">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <span class="bg-yellow-500 px-3 py-1 text-sm font-medium rounded-full">
                        {{ ucfirst($event->type) }}
                    </span>
                </div>
            </div>
        @else
            <div class="h-64 bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center relative">
                <i class="fas fa-calendar-alt text-white text-6xl"></i>
                <div class="absolute bottom-6 left-6 text-white">
                    <span class="bg-white bg-opacity-20 px-3 py-1 text-sm font-medium rounded-full">
                        {{ ucfirst($event->type) }}
                    </span>
                </div>
            </div>
        @endif

        <div class="p-8">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                    <p class="text-lg text-gray-600 mb-4">{{ $event->institution->name }}</p>
                    
                    <!-- Event Meta -->
                    <div class="flex flex-wrap items-center gap-6 text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2 text-yellow-500"></i>
                            <span>{{ $event->start_date->format('M d, Y') }}</span>
                            @if($event->start_date->format('Y-m-d') !== $event->end_date->format('Y-m-d'))
                                <span class="mx-1">-</span>
                                <span>{{ $event->end_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-yellow-500"></i>
                            <span>{{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}</span>
                        </div>
                        
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-yellow-500"></i>
                            <span>{{ $event->venue }}</span>
                        </div>
                        
                        @if($event->max_participants)
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2 text-yellow-500"></i>
                            <span>{{ $approvedRegistrations }}/{{ $event->max_participants }} participants</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center space-x-3 ml-6">
                    <button wire:click="openShareModal" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg inline-flex items-center">
                        <i class="fas fa-share-alt mr-2"></i>
                        Share
                    </button>
                    
                    <button wire:click="downloadEventCalendar" 
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg inline-flex items-center">
                        <i class="fas fa-calendar-plus mr-2"></i>
                        Add to Calendar
                    </button>

                    @can('update', $event)
                    <a href="{{ route('events.edit', $event) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Event
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Event Status Banner -->
            @if($userRegistration)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($userRegistration->status === 'approved')
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <div>
                                    <h3 class="text-lg font-semibold text-green-800">Registration Approved</h3>
                                    <p class="text-sm text-green-600">You are confirmed for this event</p>
                                </div>
                            @elseif($userRegistration->status === 'pending')
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3 animate-pulse"></div>
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-800">Registration Pending</h3>
                                    <p class="text-sm text-yellow-600">Your registration is awaiting approval</p>
                                </div>
                            @elseif($userRegistration->status === 'rejected')
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                <div>
                                    <h3 class="text-lg font-semibold text-red-800">Registration Rejected</h3>
                                    <p class="text-sm text-red-600">Your registration was not approved</p>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center space-x-3">
                            @if($userRegistration->payment_required && $userRegistration->payment_status !== 'paid')
                                <button wire:click="openPaymentModal" 
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    <i class="fas fa-credit-card mr-2"></i>
                                    Submit Payment
                                </button>
                            @endif
                            
                            @if(in_array($userRegistration->status, ['pending', 'approved']))
                                <button wire:click="cancelRegistration" 
                                        wire:confirm="Are you sure you want to cancel your registration?"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Cancel Registration
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Status -->
                    @if($userRegistration->payment_required)
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm font-medium text-gray-700">Payment Status:</span>
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                        @if($userRegistration->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($userRegistration->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($userRegistration->payment_status ?? 'Not Submitted') }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Fee: ${{ number_format($event->registration_fee, 2) }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Event Description -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">About This Event</h2>
                <div class="prose max-w-none text-gray-700">
                    {{ $event->description }}
                </div>

                @if($event->address)
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-medium text-gray-900 mb-2">Full Address</h3>
                        <p class="text-gray-700">{{ $event->address }}</p>
                    </div>
                @endif
            </div>

            <!-- Event Objectives -->
            @php
                $objectives = is_array($event->objectives) ? $event->objectives : (is_string($event->objectives) ? json_decode($event->objectives, true) : []);
            @endphp
            @if($objectives && count($objectives) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Objectives</h2>
                    <ul class="space-y-2">
                        @foreach($objectives as $objective)
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                <span class="text-gray-700">{{ $objective }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Requirements -->
            @php
                $requirements = is_array($event->requirements) ? $event->requirements : (is_string($event->requirements) ? json_decode($event->requirements, true) : []);
            @endphp
            @if($requirements && count($requirements) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Requirements</h2>
                    <ul class="space-y-2">
                        @foreach($requirements as $requirement)
                            <li class="flex items-start">
                                <i class="fas fa-exclamation-circle text-yellow-500 mr-3 mt-1"></i>
                                <span class="text-gray-700">{{ $requirement }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Target Audience -->
            @php
                $targetAudience = is_array($event->target_audience) ? $event->target_audience : (is_string($event->target_audience) ? json_decode($event->target_audience, true) : []);
            @endphp
            @if($targetAudience && count($targetAudience) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Target Audience</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($targetAudience as $audience)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 text-sm font-medium rounded-full">
                                {{ $audience }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Registration Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration</h3>
                
                @if($event->registration_start && now() < $event->registration_start)
                    <div class="text-center py-4">
                        <i class="fas fa-clock text-gray-400 text-2xl mb-2"></i>
                        <p class="text-gray-600 font-medium">Registration Opens</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $event->registration_start->format('M d, Y g:i A') }}</p>
                    </div>
                @elseif($event->registration_end && now() > $event->registration_end)
                    <div class="text-center py-4">
                        <i class="fas fa-times-circle text-red-400 text-2xl mb-2"></i>
                        <p class="text-red-600 font-medium">Registration Closed</p>
                        <p class="text-sm text-gray-500">Ended {{ $event->registration_end->format('M d, Y') }}</p>
                    </div>
                @elseif($userRegistration)
                    <div class="text-center py-4">
                        <i class="fas fa-user-check text-green-400 text-2xl mb-2"></i>
                        <p class="text-green-600 font-medium">You're Registered!</p>
                        <p class="text-sm text-gray-500">Status: {{ ucfirst($userRegistration->status) }}</p>
                    </div>
                @elseif($canRegister)
                    <div class="space-y-4">
                        @if(!$event->is_free)
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-yellow-800">Registration Fee</span>
                                    <span class="text-lg font-bold text-yellow-900">${{ number_format($event->registration_fee, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="text-center">
                                    <span class="text-lg font-bold text-green-800">FREE EVENT</span>
                                </div>
                            </div>
                        @endif

                        @if($event->max_participants)
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Available Spots</span>
                                <span class="font-semibold">{{ $event->max_participants - $approvedRegistrations }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" 
                                     style="width: {{ ($approvedRegistrations / $event->max_participants) * 100 }}%"></div>
                            </div>
                        @endif

                        <button wire:click="openRegistrationModal" 
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-4 rounded-lg">
                            <i class="fas fa-user-plus mr-2"></i>
                            Register Now
                        </button>

                        @if($event->registration_end)
                            <p class="text-xs text-gray-500 text-center">
                                Registration ends {{ $event->registration_end->format('M d, Y g:i A') }}
                            </p>
                        @endif
                    </div>
                @else
                    @guest
                        <div class="text-center py-4">
                            <i class="fas fa-sign-in-alt text-gray-400 text-2xl mb-2"></i>
                            <p class="text-gray-600 font-medium mb-2">Login Required</p>
                            <a href="{{ route('login') }}" 
                               class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg inline-block">
                                Login to Register
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-amber-400 text-2xl mb-2"></i>
                            <p class="text-gray-600 font-medium">Registration Not Available</p>
                            <p class="text-sm text-gray-500">
                                @if($event->status !== 'published')
                                    Event not published yet
                                @elseif(!$event->allow_non_members)
                                    Members only event
                                @elseif($event->max_participants && $approvedRegistrations >= $event->max_participants)
                                    Event is full
                                @else
                                    Registration requirements not met
                                @endif
                            </p>
                        </div>
                    @endguest
                @endif
            </div>

            <!-- Event Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Details</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Organizer</span>
                        <span class="text-sm font-medium text-gray-900">{{ $event->creator->name }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Institution</span>
                        <span class="text-sm font-medium text-gray-900">{{ $event->institution->name }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Event Type</span>
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 text-xs font-medium rounded-full">
                            {{ ucfirst($event->type) }}
                        </span>
                    </div>
                    
                    @if($event->requires_approval)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Approval</span>
                            <span class="text-xs text-amber-600 font-medium">Required</span>
                        </div>
                    @endif
                    
                    @if(!$event->allow_non_members)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Eligibility</span>
                            <span class="text-xs text-blue-600 font-medium">Members Only</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Registrations -->
            @if($recentRegistrations->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Registrations</h3>
                    
                    <div class="space-y-3">
                        @foreach($recentRegistrations as $registration)
                            <div class="flex items-center space-x-3">
                                @if($registration->user->profile_photo)
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ Storage::url($registration->user->profile_photo) }}" 
                                         alt="{{ $registration->user->name }}">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <span class="text-yellow-600 font-medium text-xs">
                                            {{ substr($registration->user->name, 0, 2) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $registration->user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $registration->registered_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($approvedRegistrations > 10)
                        <div class="mt-4 text-center">
                            <span class="text-sm text-gray-500">
                                +{{ $approvedRegistrations - 10 }} more registered
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Tags -->
            @php
                $tags = is_array($event->tags) ? $event->tags : (is_string($event->tags) ? json_decode($event->tags, true) : []);
            @endphp
            @if($tags && count($tags) > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 text-sm rounded-full">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Registration Modal -->
    @if($showRegistrationModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" wire:click="closeRegistrationModal">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Register for Event</h2>
                            <p class="text-sm text-gray-600">{{ $event->title }}</p>
                        </div>
                        <button wire:click="closeRegistrationModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="register" class="space-y-6">
                        <!-- Emergency Contact -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                            <input type="text" 
                                   wire:model="emergencyContact" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Name and phone number">
                            @error('emergencyContact') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Dietary Requirements -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dietary Requirements</label>
                            <textarea wire:model="dietaryRequirements" 
                                      rows="2"
                                      class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Any dietary restrictions or allergies..."></textarea>
                            @error('dietaryRequirements') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Special Needs -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Special Needs/Accessibility</label>
                            <textarea wire:model="specialNeeds" 
                                      rows="2"
                                      class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Any special accommodations needed..."></textarea>
                            @error('specialNeeds') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Information</label>
                            <textarea wire:model="additionalInfo" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Anything else you'd like us to know..."></textarea>
                            @error('additionalInfo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="flex items-start">
                                <input type="checkbox" 
                                       wire:model="agreeToTerms" 
                                       class="mt-1 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                <span class="ml-2 text-sm text-gray-700">
                                    I agree to the event terms and conditions, and confirm that the information provided is accurate.
                                    @if($event->requires_approval)
                                        I understand that my registration is subject to approval.
                                    @endif
                                    @if(!$event->is_free)
                                        I acknowledge that payment of ${{ number_format($event->registration_fee, 2) }} is required.
                                    @endif
                                </span>
                            </label>
                            @error('agreeToTerms') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-3 px-4 rounded-lg">
                                <i class="fas fa-user-plus mr-2"></i>
                                Complete Registration
                            </button>
                            <button type="button" 
                                    wire:click="closeRegistrationModal"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" wire:click="closePaymentModal">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" wire:click.stop>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Submit Payment Proof</h2>
                            <p class="text-sm text-gray-600">Event: {{ $event->title }}</p>
                            <p class="text-lg font-semibold text-yellow-600">Amount: ${{ number_format($event->registration_fee, 2) }}</p>
                        </div>
                        <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="bg-blue-50 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-blue-900 mb-2">Payment Instructions</h3>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p><strong>Bank Transfer:</strong> Account No: 123-456-789, Bank: ABC Bank</p>
                            <p><strong>Mobile Money:</strong> +255 123 456 789</p>
                            <p><strong>Reference:</strong> Use your name and event title</p>
                            <p class="mt-2 font-medium">After payment, upload a screenshot as proof below.</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="submitPayment" class="space-y-6">
                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                            <select wire:model="paymentMethod" 
                                    class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Select payment method</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="credit_card">Credit/Debit Card</option>
                                <option value="cash">Cash Payment</option>
                            </select>
                            @error('paymentMethod') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Payment Reference -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Reference/Transaction ID <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   wire:model="paymentReference" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter transaction reference number">
                            @error('paymentReference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Payment Screenshot -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Screenshot <span class="text-red-500">*</span></label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-yellow-400 transition-colors">
                                <input type="file" 
                                       wire:model="paymentScreenshot" 
                                       accept="image/*"
                                       class="hidden" 
                                       id="payment-screenshot">
                                <label for="payment-screenshot" class="cursor-pointer">
                                    @if($paymentScreenshot)
                                        <div class="space-y-2">
                                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                            <p class="text-sm text-green-600 font-medium">Image selected: {{ $paymentScreenshot->getClientOriginalName() }}</p>
                                            <p class="text-xs text-gray-500">Click to change</p>
                                        </div>
                                    @else
                                        <div class="space-y-2">
                                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl"></i>
                                            <p class="text-sm text-gray-600">Click to upload payment screenshot</p>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                        </div>
                                    @endif
                                </label>
                            </div>
                            @error('paymentScreenshot') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Payment Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                            <textarea wire:model="paymentNotes" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Any additional information about your payment..."></textarea>
                            @error('paymentNotes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg">
                                <i class="fas fa-upload mr-2"></i>
                                Submit Payment Proof
                            </button>
                            <button type="button" 
                                    wire:click="closePaymentModal"
                                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Share Modal -->
    @if($showShareModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50" wire:click="closeShareModal">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" wire:click.stop>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Share Event</h2>
                        <button wire:click="closeShareModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Copy Link -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Link</label>
                            <div class="flex">
                                <input type="text" 
                                       value="{{ $this->getShareUrl() }}" 
                                       class="flex-1 rounded-l-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                       readonly>
                                <button onclick="copyToClipboard('{{ $this->getShareUrl() }}')"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-r-lg">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Social Share Buttons -->
                        <div class="grid grid-cols-3 gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($this->getShareUrl()) }}" 
                               target="_blank"
                               class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-center">
                                <i class="fab fa-facebook-f mr-2"></i>Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode($this->getShareUrl()) }}&text={{ urlencode($event->title) }}" 
                               target="_blank"
                               class="bg-blue-400 hover:bg-blue-500 text-white py-2 px-4 rounded-lg text-center">
                                <i class="fab fa-twitter mr-2"></i>Twitter
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($event->title . ' - ' . $this->getShareUrl()) }}" 
                               target="_blank"
                               class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-center">
                                <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                            </a>
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

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const event = new CustomEvent('copy-success');
        window.dispatchEvent(event);
    });
}

// Show copy success message
window.addEventListener('copy-success', function() {
    // You can implement a toast notification here
    alert('Link copied to clipboard!');
});
</script>