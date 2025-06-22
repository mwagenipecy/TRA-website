<div>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Issue Certificate</h1>
        <a href="{{ route('certificates.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Certificates
        </a>
    </div>

    <form wire:submit.prevent="issueCertificate">
        {{-- Certificate Information --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-certificate text-yellow-500 mr-2"></i>Certificate Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Title *</label>
                    <input type="text" wire:model="title" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Type *</label>
                    <select wire:model="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="completion">Completion Certificate</option>
                        <option value="participation">Participation Certificate</option>
                        <option value="achievement">Achievement Certificate</option>
                        <option value="recognition">Recognition Certificate</option>
                    </select>
                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                    <input type="date" wire:model="issue_date" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    @error('issue_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date (Optional)</label>
                    <input type="date" wire:model="expiry_date" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    @error('expiry_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Related Event (Optional)</label>
                    <select wire:model="event_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="">Select Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                    <select wire:model="template_used" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="default">Default Template</option>
                        <option value="formal">Formal Template</option>
                        <option value="modern">Modern Template</option>
                        <option value="classic">Classic Template</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Special Notes</label>
                <textarea wire:model="special_notes" rows="2" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
            </div>
        </div>

        {{-- Certificate Data --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-info-circle text-yellow-500 mr-2"></i>Certificate Details
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Course/Program Name</label>
                    <input type="text" wire:model="certificate_data.course_name" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                    <input type="text" wire:model="certificate_data.duration" placeholder="e.g., 3 months, 40 hours"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Grade/Score</label>
                    <input type="text" wire:model="certificate_data.grade" placeholder="e.g., A, 85%, Excellent"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Instructor/Facilitator</label>
                    <input type="text" wire:model="certificate_data.instructor" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Achievement Details</label>
                <textarea wire:model="certificate_data.achievement_details" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
            </div>
        </div>

        {{-- Recipients --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800 border-b border-yellow-200 pb-2">
                    <i class="fas fa-users text-yellow-500 mr-2"></i>Recipients
                </h2>
                <label class="flex items-center">
                    <input type="checkbox" wire:model="bulkIssue" wire:click="toggleBulkIssue" class="form-checkbox h-4 w-4 text-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">Bulk Issue</span>
                </label>
            </div>
            
            @if(!$bulkIssue)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Recipient *</label>
                <select wire:model="user_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">Choose a user</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @else
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Recipients *</label>
                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-4">
                    @foreach($users as $user)
                    <label class="flex items-center py-2 hover:bg-gray-50">
                        <input type="checkbox" 
                               wire:click="addUser({{ $user->id }})" 
                               @if(in_array($user->id, $selectedUsers)) checked @endif
                               class="form-checkbox h-4 w-4 text-yellow-500">
                        <span class="ml-3 text-sm text-gray-700">{{ $user->name }} ({{ $user->email }})</span>
                    </label>
                    @endforeach
                </div>
                @error('selectedUsers') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                
                @if(count($selectedUsers) > 0)
                <div class="mt-4">
                    <span class="text-sm font-medium text-gray-700">Selected Recipients ({{ count($selectedUsers) }}):</span>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($selectedUsers as $userId)
                        @php $user = $users->find($userId) @endphp
                        @if($user)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                            {{ $user->name }}
                            <button type="button" wire:click="removeUser({{ $userId }})" class="ml-2 text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </span>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- File Upload --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-upload text-yellow-500 mr-2"></i>Certificate File (Optional)
            </h2>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Certificate File</label>
                <input type="file" wire:model="certificate_file" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Supported formats: PDF, JPG, PNG (Max: 5MB)</p>
                @error('certificate_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            @if($certificate_file)
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-file text-green-500 mr-2"></i>
                    <span class="text-sm text-green-700">File ready for upload: {{ $certificate_file->getClientOriginalName() }}</span>
                </div>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('certificates.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-certificate mr-2"></i>Issue Certificate{{ $bulkIssue && count($selectedUsers) > 1 ? 's' : '' }}
                </button>
            </div>
        </div>
    </form>
</div>


</div>
