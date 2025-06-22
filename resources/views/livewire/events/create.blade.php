<div>
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-900">Create New Event</h1>
        <p class="mt-2 text-gray-600">Organize workshops, seminars, training sessions and more</p>
    </div>

    <!-- Progress Steps -->
    <div class="flex items-center justify-center space-x-8">
        @for($i = 1; $i <= $totalSteps; $i++)
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $step >= $i ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-600' }}">
                    @if($step > $i)
                        <i class="fas fa-check"></i>
                    @else
                        {{ $i }}
                    @endif
                </div>
                <div class="ml-2">
                    <p class="text-sm font-medium {{ $step >= $i ? 'text-yellow-600' : 'text-gray-500' }}">
                        @if($i == 1) Basic Info
                        @elseif($i == 2) Date & Location
                        @elseif($i == 3) Registration
                        @else Additional Details
                        @endif
                    </p>
                </div>
                @if($i < $totalSteps)
                    <div class="w-8 h-px {{ $step > $i ? 'bg-yellow-500' : 'bg-gray-200' }} ml-4"></div>
                @endif
            </div>
        @endfor
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <form wire:submit.prevent="{{ $step == $totalSteps ? 'submit' : 'nextStep' }}">
            @if($step == 1)
                <!-- Step 1: Basic Information -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Event Title -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Title <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   wire:model="title" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter event title">
                            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Event Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Type <span class="text-red-500">*</span></label>
                            <select wire:model="type" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="workshop">Workshop</option>
                                <option value="seminar">Seminar</option>
                                <option value="training">Training</option>
                                <option value="conference">Conference</option>
                                <option value="meeting">Meeting</option>
                                <option value="competition">Competition</option>
                                <option value="other">Other</option>
                            </select>
                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Institution -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                            <select wire:model="institution_id" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Select institution</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }} ({{ $institution->type }})</option>
                                @endforeach
                            </select>
                            @error('institution_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Event Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Event Description <span class="text-red-500">*</span></label>
                            <textarea wire:model="description" 
                                      rows="5"
                                      class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Provide a detailed description of the event (minimum 50 characters)"></textarea>
                            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-sm text-gray-500">{{ strlen($description) }}/50 characters minimum</p>
                        </div>
                    </div>
                </div>

            @elseif($step == 2)
                <!-- Step 2: Date & Location -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Date & Location</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Date & Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" 
                                   wire:model="start_date" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                            <input type="time" 
                                   wire:model="start_time" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @error('start_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- End Date & Time -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Date <span class="text-red-500">*</span></label>
                            <input type="date" 
                                   wire:model="end_date" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                            <input type="time" 
                                   wire:model="end_time" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @error('end_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Venue -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Venue <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   wire:model="venue" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Event venue or location">
                            @error('venue') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                            <textarea wire:model="address" 
                                      rows="2"
                                      class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                      placeholder="Complete address with city, region, etc."></textarea>
                            @error('address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Coordinates (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                            <input type="number" 
                                   step="any"
                                   wire:model="latitude" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Optional GPS latitude">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                            <input type="number" 
                                   step="any"
                                   wire:model="longitude" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Optional GPS longitude">
                        </div>
                    </div>
                </div>

            @elseif($step == 3)
                <!-- Step 3: Registration Details -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Registration Settings</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Max Participants -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Maximum Participants</label>
                            <input type="number" 
                                   wire:model="max_participants" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Leave empty for unlimited">
                            @error('max_participants') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Registration Fee -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Registration Fee</label>
                            <div class="flex items-center space-x-3">
                                <label class="flex items-center">
                                    <input type="checkbox" 
                                           wire:model.live="is_free" 
                                           class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                    <span class="ml-2 text-sm text-gray-700">Free Event</span>
                                </label>
                            </div>
                            @if(!$is_free)
                            <input type="number" 
                                   step="0.01"
                                   wire:model="registration_fee" 
                                   class="mt-2 w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="0.00">
                            @endif
                            @error('registration_fee') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Registration Period -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Registration Start</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" 
                                       wire:model="registration_start_date" 
                                       class="rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <input type="time" 
                                       wire:model="registration_start_time" 
                                       class="rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                            </div>
                            @error('registration_start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Registration End</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="date" 
                                       wire:model="registration_end_date" 
                                       class="rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <input type="time" 
                                       wire:model="registration_end_time" 
                                       class="rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                            </div>
                            @error('registration_end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Registration Options -->
                        <div class="md:col-span-2 space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="requires_approval" 
                                       class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                <span class="ml-2 text-sm text-gray-700">Require approval for registration</span>
                            </label>
                            
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       wire:model="allow_non_members" 
                                       class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                <span class="ml-2 text-sm text-gray-700">Allow non-members to register</span>
                            </label>
                        </div>
                    </div>
                </div>

            @else
                <!-- Step 4: Additional Details -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Additional Details</h2>
                    
                    <!-- Event Banner -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event Banner</label>
                        <input type="file" 
                               wire:model="banner_image" 
                               accept="image/*"
                               class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('banner_image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        @if($banner_image)
                            <div class="mt-2">
                                <img src="{{ $banner_image->temporaryUrl() }}" class="h-32 w-auto rounded-lg">
                            </div>
                        @endif
                    </div>

                    <!-- Objectives -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Event Objectives</label>
                        <div class="flex space-x-2 mb-2">
                            <input type="text" 
                                   wire:model="newObjective" 
                                   wire:keydown.enter.prevent="addObjective"
                                   class="flex-1 rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Add an objective">
                            <button type="button" 
                                    wire:click="addObjective"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">
                                Add
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($objectives as $index => $objective)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                                    {{ $objective }}
                                    <button type="button" 
                                            wire:click="removeObjective({{ $index }})"
                                            class="ml-2 text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Requirements</label>
                        <div class="flex space-x-2 mb-2">
                            <input type="text" 
                                   wire:model="newRequirement" 
                                   wire:keydown.enter.prevent="addRequirement"
                                   class="flex-1 rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Add a requirement">
                            <button type="button" 
                                    wire:click="addRequirement"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                Add
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($requirements as $index => $requirement)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                    {{ $requirement }}
                                    <button type="button" 
                                            wire:click="removeRequirement({{ $index }})"
                                            class="ml-2 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Target Audience -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Audience</label>
                        <div class="flex space-x-2 mb-2">
                            <input type="text" 
                                   wire:model="newTargetAudience" 
                                   wire:keydown.enter.prevent="addTargetAudience"
                                   class="flex-1 rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Add target audience">
                            <button type="button" 
                                    wire:click="addTargetAudience"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                                Add
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($target_audience as $index => $audience)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                    {{ $audience }}
                                    <button type="button" 
                                            wire:click="removeTargetAudience({{ $index }})"
                                            class="ml-2 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tags -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        <div class="flex space-x-2 mb-2">
                            <input type="text" 
                                   wire:model="newTag" 
                                   wire:keydown.enter.prevent="addTag"
                                   class="flex-1 rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Add a tag">
                            <button type="button" 
                                    wire:click="addTag"
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium">
                                Add
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($tags as $index => $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-purple-100 text-purple-800">
                                    {{ $tag }}
                                    <button type="button" 
                                            wire:click="removeTag({{ $index }})"
                                            class="ml-2 text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Navigation -->
            <div class="flex justify-between pt-8 mt-8 border-t border-gray-200">
                <div>
                    @if($step > 1)
                        <button type="button" 
                                wire:click="previousStep"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg inline-flex items-center font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Previous
                        </button>
                    @endif
                </div>
                
                <div>
                    @if($step < $totalSteps)
                        <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg inline-flex items-center font-medium">
                            Next
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    @else
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg inline-flex items-center font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Create Event
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Loading States -->
    <div wire:loading.flex class="fixed inset-0 bg-gray-500 bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-500"></div>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>
</div>
</div>
