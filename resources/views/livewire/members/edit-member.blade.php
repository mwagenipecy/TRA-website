<div>
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Member</h1>
            <p class="mt-2 text-gray-600">Update member information and settings</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            @if($member->status === 'pending' && $member->canBeApprovedBy(auth()->user()))
                <button wire:click="approveMember" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    Approve Member
                </button>
                <button wire:click="rejectMember" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Reject
                </button>
            @endif
        </div>
    </div>

    <!-- Member Info Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                @if($member->user->profile_photo)
                    <img class="h-16 w-16 rounded-full object-cover" 
                         src="{{ Storage::url($member->user->profile_photo) }}" 
                         alt="{{ $member->user->name }}">
                @else
                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-xl font-medium text-gray-700">
                            {{ substr($member->user->name, 0, 2) }}
                        </span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900">{{ $member->user->name }}</h2>
                <p class="text-gray-600">{{ $member->user->email }}</p>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $member->status_badge }}">
                        {{ ucfirst($member->status) }}
                    </span>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $member->member_type_badge }}">
                        {{ ucfirst($member->member_type) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        <form wire:submit.prevent="updateMember">
            <div class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Institution -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                            <select wire:model="institution_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
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
                            <select wire:model="member_type" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="student">Student</option>
                                <option value="leader">Leader</option>
                                <option value="supervisor">Supervisor</option>
                            </select>
                            @error('member_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select wire:model="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="graduated">Graduated</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Student ID -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student/Staff ID</label>
                            <input type="text" 
                                   wire:model="student_id" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Enter student or staff ID">
                            @error('student_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Course of Study -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course of Study</label>
                            <input type="text" 
                                   wire:model="course_of_study" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Enter course or department">
                            @error('course_of_study') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Year of Study -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year of Study</label>
                            <select wire:model="year_of_study" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select year</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">Year {{ $i }}</option>
                                @endfor
                            </select>
                            @error('year_of_study') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Joined Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Joined Date</label>
                            <input type="date" 
                                   wire:model="joined_date" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('joined_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- Graduation Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Graduation Date</label>
                            <input type="date" 
                                   wire:model="graduation_date" 
                                   class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            @error('graduation_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Interests -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Interests</h3>
                    <div class="flex space-x-2 mb-2">
                        <input type="text" 
                               wire:model="newInterest" 
                               wire:keydown.enter.prevent="addInterest"
                               class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Add an interest">
                        <button type="button" 
                                wire:click="addInterest"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Add
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($interests as $index => $interest)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                {{ $interest }}
                                <button type="button" 
                                        wire:click="removeInterest({{ $index }})"
                                        class="ml-2 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Skills -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Skills</h3>
                    <div class="flex space-x-2 mb-2">
                        <input type="text" 
                               wire:model="newSkill" 
                               wire:keydown.enter.prevent="addSkill"
                               class="flex-1 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                               placeholder="Add a skill">
                        <button type="button" 
                                wire:click="addSkill"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                            Add
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-2">
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Motivation</h3>
                    <textarea wire:model="motivation" 
                              rows="4"
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Member's motivation and goals..."></textarea>
                    @error('motivation') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Approval Notes -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Approval Notes</h3>
                    <textarea wire:model="approval_notes" 
                              rows="3"
                              class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Notes about approval, rejection, or general comments..."></textarea>
                    @error('approval_notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between pt-8 mt-8 border-t border-gray-200">
                <a href="{{ route('members.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Members
                </a>
                
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Member
                </button>
            </div>
        </form>
    </div>
</div>
</div>
