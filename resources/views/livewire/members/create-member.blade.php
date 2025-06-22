<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-900">Add New Member</h1>
        <p class="mt-2 text-gray-600">Create a new member account for the organization</p>
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
                        @if($i == 1) Personal Info
                        @elseif($i == 2) Member Details
                        @else Additional Info
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
                <!-- Step 1: Personal Information -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   wire:model="name" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter full name">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" 
                                   wire:model="email" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter email address">
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" 
                                   wire:model="phone" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter phone number">
                            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- National ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">National ID</label>
                            <input type="text" 
                                   wire:model="national_id" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter national ID">
                            @error('national_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" 
                                   wire:model="date_of_birth" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                            @error('date_of_birth') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select wire:model="gender" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                            @error('gender') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" 
                                   wire:model="password" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter password">
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                            <input type="password" 
                                   wire:model="password_confirmation" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Confirm password">
                            @error('password_confirmation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            @elseif($step == 2)
                <!-- Step 2: Member Details -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Member Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Institution -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                            <select wire:model="institution_id" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Select institution</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }} ({{ $institution->type }})</option>
                                @endforeach
                            </select>
                            @error('institution_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Member Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Member Type <span class="text-red-500">*</span></label>
                            <select wire:model="member_type" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="student">Student</option>
                                <option value="leader">Leader</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                            @error('member_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Student ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student/Staff ID</label>
                            <input type="text" 
                                   wire:model="student_id" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter student or staff ID">
                            @error('student_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Course of Study -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course of Study</label>
                            <input type="text" 
                                   wire:model="course_of_study" 
                                   class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Enter course or department">
                            @error('course_of_study') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Year of Study -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year of Study</label>
                            <select wire:model="year_of_study" class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Select year</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">Year {{ $i }}</option>
                                @endfor
                            </select>
                            @error('year_of_study') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

            @else
                <!-- Step 3: Additional Information -->
                <div class="space-y-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Additional Information</h2>
                    
                    <!-- Interests -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interests</label>
                        <div class="flex space-x-2 mb-2">
                            <input type="text" 
                                   wire:model="newInterest" 
                                   wire:keydown.enter.prevent="addInterest"
                                   class="flex-1 rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Add an interest">
                            <button type="button" 
                                    wire:click="addInterest"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">
                                Add
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($interests as $index => $interest)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                                    {{ $interest }}
                                    <button type="button" 
                                            wire:click="removeInterest({{ $index }})"
                                            class="ml-2 text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Skills -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Skills</label>
                        <div class="flex space-x-2 mb-2">
                            <input type="text" 
                                   wire:model="newSkill" 
                                   wire:keydown.enter.prevent="addSkill"
                                   class="flex-1 rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Add a skill">
                            <button type="button" 
                                    wire:click="addSkill"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                                Add
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($skills as $index => $skill)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                    {{ $skill }}
                                    <button type="button" 
                                            wire:click="removeSkill({{ $index }})"
                                            class="ml-2 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Motivation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Motivation</label>
                        <textarea wire:model="motivation" 
                                  rows="4"
                                  class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500"
                                  placeholder="Why do you want to join this organization? What are your goals?"></textarea>
                        @error('motivation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
                            Create Member
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>