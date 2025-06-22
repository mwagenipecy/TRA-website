<div>
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Add New Institution</h1>
            <p class="text-sm text-gray-600">Register a new educational institution for the tax club program</p>
        </div>
        <a href="{{ route('institutions.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Institutions
        </a>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            @for($i = 1; $i <= $totalSteps; $i++)
                <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full 
                        {{ $currentStep >= $i ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-600' }}
                        {{ $this->isStepAccessible($i) ? 'cursor-pointer hover:bg-yellow-600' : '' }}"
                         @if($this->isStepAccessible($i)) wire:click="goToStep({{ $i }})" @endif>
                        @if($this->isStepCompleted($i) && $currentStep > $i)
                            <i class="fas fa-check text-sm"></i>
                        @else
                            <span class="text-sm font-medium">{{ $i }}</span>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium 
                            {{ $currentStep >= $i ? 'text-gray-900' : 'text-gray-500' }}">
                            {{ $this->getStepTitle($i) }}
                        </div>
                    </div>
                    @if($i < $totalSteps)
                        <div class="flex-1 h-px bg-gray-200 ml-6"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <!-- Form Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <form wire:submit="save">
            
            @if($currentStep === 1)
                <!-- Step 1: Basic Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Institution Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Institution Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model.blur="name" 
                                       id="name"
                                       placeholder="e.g., University of Dar es Salaam"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('name') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Institution Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Institution Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model.blur="code" 
                                       id="code"
                                       placeholder="e.g., UDSM"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500 uppercase">
                                @error('code') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Unique identifier for the institution</p>
                            </div>

                            <!-- Institution Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Institution Type <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="type" 
                                        id="type"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                    @foreach($this->getInstitutionTypes() as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Established Date -->
                            <div>
                                <label for="established_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Established Date
                                </label>
                                <input type="date" 
                                       wire:model="established_date" 
                                       id="established_date"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('established_date') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea wire:model="description" 
                                          id="description"
                                          rows="3"
                                          placeholder="Brief description of the institution and its focus areas..."
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                                @error('description') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Maximum 1000 characters</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep === 2)
                <!-- Step 2: Location Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Location Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Street Address <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="address" 
                                       id="address"
                                       placeholder="e.g., University Road, Mlimani"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('address') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="city" 
                                       id="city"
                                       placeholder="e.g., Dar es Salaam"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('city') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Region -->
                            <div>
                                <label for="region" class="block text-sm font-medium text-gray-700 mb-1">
                                    Region <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="region" 
                                        id="region"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="">Select Region</option>
                                    @foreach($this->getTanzanianRegions() as $regionName)
                                        <option value="{{ $regionName }}">{{ $regionName }}</option>
                                    @endforeach
                                </select>
                                @error('region') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Postal Code
                                </label>
                                <input type="text" 
                                       wire:model="postal_code" 
                                       id="postal_code"
                                       placeholder="e.g., 12345"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('postal_code') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep === 3)
                <!-- Step 3: Contact Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number
                                </label>
                                <input type="tel" 
                                       wire:model="phone" 
                                       id="phone"
                                       placeholder="e.g., +255 22 241 0500"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('phone') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address
                                </label>
                                <input type="email" 
                                       wire:model="email" 
                                       id="email"
                                       placeholder="e.g., info@institution.ac.tz"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('email') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 mb-1">
                                    Website URL
                                </label>
                                <input type="url" 
                                       wire:model="website" 
                                       id="website"
                                       placeholder="e.g., https://www.institution.ac.tz"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                @error('website') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                            </div>

                            <!-- Logo Upload -->
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">
                                    Institution Logo
                                </label>
                                <div class="flex items-center space-x-4">
                                    <input type="file" 
                                           wire:model="logo" 
                                           id="logo"
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                                    @if($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="h-12 w-12 object-cover rounded-md">
                                    @endif
                                </div>
                                @error('logo') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>

                        <!-- Contact Persons -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-base font-medium text-gray-900">Contact Persons</h4>
                                <button type="button" 
                                        wire:click="addContactPerson"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Contact
                                </button>
                            </div>

                            <div class="space-y-4">
                                @foreach($contactPersons as $index => $contact)
                                    <div class="p-4 border border-gray-200 rounded-md bg-gray-50">
                                        <div class="flex items-center justify-between mb-3">
                                            <h5 class="text-sm font-medium text-gray-700">Contact Person {{ $index + 1 }}</h5>
                                            @if(count($contactPersons) > 1)
                                                <button type="button" 
                                                        wire:click="removeContactPerson({{ $index }})"
                                                        class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                                <input type="text" 
                                                       wire:model="contactPersons.{{ $index }}.name"
                                                       placeholder="Full name"
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                                @error("contactPersons.{$index}.name") 
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                                @enderror
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Title/Position</label>
                                                <input type="text" 
                                                       wire:model="contactPersons.{{ $index }}.title"
                                                       placeholder="e.g., Dean, Director"
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                                @error("contactPersons.{$index}.title") 
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                                @enderror
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                <input type="email" 
                                                       wire:model="contactPersons.{{ $index }}.email"
                                                       placeholder="email@institution.ac.tz"
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                                @error("contactPersons.{$index}.email") 
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                                @enderror
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                                <input type="tel" 
                                                       wire:model="contactPersons.{{ $index }}.phone"
                                                       placeholder="+255 XXX XXX XXX"
                                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                                @error("contactPersons.{$index}.phone") 
                                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($currentStep === 4)
                <!-- Step 4: Review & Submit -->
                <div class="p-6 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Review Information</h3>
                        
                        <div class="space-y-6">
                            <!-- Basic Information Review -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-base font-medium text-gray-900 mb-3">Basic Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Name:</span>
                                        <span class="ml-2 text-gray-900">{{ $name }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Code:</span>
                                        <span class="ml-2 text-gray-900">{{ $code }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Type:</span>
                                        <span class="ml-2 text-gray-900">{{ $this->getInstitutionTypes()[$type] ?? $type }}</span>
                                    </div>
                                    @if($established_date)
                                        <div>
                                            <span class="font-medium text-gray-700">Established:</span>
                                            <span class="ml-2 text-gray-900">{{ \Carbon\Carbon::parse($established_date)->format('Y') }}</span>
                                        </div>
                                    @endif
                                    @if($description)
                                        <div class="md:col-span-2">
                                            <span class="font-medium text-gray-700">Description:</span>
                                            <p class="mt-1 text-gray-900">{{ $description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Location Information Review -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-base font-medium text-gray-900 mb-3">Location</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div class="md:col-span-2">
                                        <span class="font-medium text-gray-700">Address:</span>
                                        <span class="ml-2 text-gray-900">{{ $address }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">City:</span>
                                        <span class="ml-2 text-gray-900">{{ $city }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Region:</span>
                                        <span class="ml-2 text-gray-900">{{ $region }}</span>
                                    </div>
                                    @if($postal_code)
                                        <div>
                                            <span class="font-medium text-gray-700">Postal Code:</span>
                                            <span class="ml-2 text-gray-900">{{ $postal_code }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact Information Review -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-base font-medium text-gray-900 mb-3">Contact Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    @if($phone)
                                        <div>
                                            <span class="font-medium text-gray-700">Phone:</span>
                                            <span class="ml-2 text-gray-900">{{ $phone }}</span>
                                        </div>
                                    @endif
                                    @if($email)
                                        <div>
                                            <span class="font-medium text-gray-700">Email:</span>
                                            <span class="ml-2 text-gray-900">{{ $email }}</span>
                                        </div>
                                    @endif
                                    @if($website)
                                        <div class="md:col-span-2">
                                            <span class="font-medium text-gray-700">Website:</span>
                                            <a href="{{ $website }}" target="_blank" class="ml-2 text-yellow-600 hover:text-yellow-800">{{ $website }}</a>
                                        </div>
                                    @endif
                                </div>

                                @if($logo)
                                    <div class="mt-4">
                                        <span class="font-medium text-gray-700">Logo:</span>
                                        <div class="mt-2">
                                            <img src="{{ $logo->temporaryUrl() }}" alt="Institution Logo" class="h-16 w-16 object-cover rounded-md border border-gray-200">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Contact Persons Review -->
                            @if(!empty(array_filter($contactPersons, fn($person) => !empty($person['email']) || !empty($person['name']))))
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-base font-medium text-gray-900 mb-3">Contact Persons</h4>
                                    <div class="space-y-3">
                                        @foreach($contactPersons as $contact)
                                            @if(!empty($contact['email']) || !empty($contact['name']))
                                                <div class="text-sm">
                                                    <div class="font-medium text-gray-900">{{ $contact['name'] }}</div>
                                                    @if($contact['title'])
                                                        <div class="text-gray-600">{{ $contact['title'] }}</div>
                                                    @endif
                                                    <div class="text-gray-600">
                                                        @if($contact['email'])
                                                            <span>{{ $contact['email'] }}</span>
                                                        @endif
                                                        @if($contact['phone'])
                                                            <span class="ml-3">{{ $contact['phone'] }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Status Information -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h4 class="text-base font-medium text-blue-900 mb-2">Application Status</h4>
                                <p class="text-sm text-blue-700">
                                    @if(auth()->user()->isTraOfficer())
                                        As a TRA Officer, this institution will be automatically approved upon submission.
                                    @else
                                        This institution will be submitted for approval by TRA. You will receive an email notification once the application has been reviewed.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <div>
                    @if($currentStep > 1)
                        <button type="button" 
                                wire:click="previousStep"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Previous
                        </button>
                    @endif
                </div>

                <div class="flex items-center space-x-3">
                    @if($currentStep < $totalSteps)
                        <button type="button" 
                                wire:click="nextStep"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400">
                            Next
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    @else
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-black bg-primary-yellow hover:bg-yellow-400"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-check mr-2"></i>
                                Submit Institution
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                Creating...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

</div>
