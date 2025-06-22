<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pending Approvals</h1>
            <p class="mt-1 text-sm text-gray-600">Review and approve new member applications</p>
        </div>
        @if(count($bulkSelected) > 0)
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <button wire:click="bulkApprove" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    Approve Selected ({{ count($bulkSelected) }})
                </button>
            </div>
        @endif
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by name, email, or student ID..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm">
                </div>
            </div>
            @if($pendingMembers->count() > 0)
                <div class="flex items-center text-sm text-gray-600">
                    <i class="fas fa-clock mr-2 text-yellow-500"></i>
                    {{ $pendingMembers->total() }} pending applications
                </div>
            @endif
        </div>
    </div>

    @if($pendingMembers->count() > 0)
        <!-- Pending Members Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($pendingMembers as $member)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <!-- Card Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" 
                                       wire:model.live="bulkSelected"
                                       value="{{ $member->id }}"
                                       class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500">
                                
                                <div class="flex-shrink-0">
                                    @if($member->user->profile_photo)
                                        <img class="h-12 w-12 rounded-full object-cover" 
                                             src="{{ Storage::url($member->user->profile_photo) }}" 
                                             alt="{{ $member->user->name }}">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center">
                                            <span class="text-lg font-semibold text-white">
                                                {{ substr($member->user->name, 0, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">
                                        {{ $member->user->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 truncate">{{ $member->user->email }}</p>
                                </div>
                            </div>
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $member->member_type === 'student' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $member->member_type === 'leader' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $member->member_type === 'supervisor' ? 'bg-indigo-100 text-indigo-800' : '' }}">
                                {{ ucfirst($member->member_type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 space-y-4">
                        <!-- Institution Info -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-university w-4 h-4 mr-2 text-yellow-500"></i>
                            <span class="font-medium">{{ $member->institution->name }}</span>
                        </div>

                        <!-- Student ID -->
                        @if($member->student_id)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-id-card w-4 h-4 mr-2 text-yellow-500"></i>
                                <span>ID: {{ $member->student_id }}</span>
                            </div>
                        @endif

                        <!-- Course Info -->
                        @if($member->course_of_study)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-graduation-cap w-4 h-4 mr-2 text-yellow-500"></i>
                                <span>{{ $member->course_of_study }}</span>
                                @if($member->year_of_study)
                                    <span class="ml-1">(Year {{ $member->year_of_study }})</span>
                                @endif
                            </div>
                        @endif

                        <!-- Application Date -->
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar w-4 h-4 mr-2 text-gray-400"></i>
                            <span>Applied {{ $member->created_at->diffForHumans() }}</span>
                        </div>

                        <!-- Motivation Preview -->
                        @if($member->motivation)
                            <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                                <h4 class="text-xs font-semibold text-yellow-800 uppercase tracking-wide mb-1">Motivation</h4>
                                <p class="text-sm text-gray-700 line-clamp-3">
                                    {{ Str::limit($member->motivation, 120) }}
                                </p>
                            </div>
                        @endif

                        <!-- Interests & Skills -->
                        @if($member->interests || $member->skills)
                            <div class="space-y-2">
                                @if($member->interests)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Interests</h4>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($member->interests, 0, 3) as $interest)
                                                <span class="inline-flex px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                                    {{ $interest }}
                                                </span>
                                            @endforeach
                                            @if(count($member->interests) > 3)
                                                <span class="inline-flex px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                                    +{{ count($member->interests) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($member->skills)
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Skills</h4>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(array_slice($member->skills, 0, 3) as $skill)
                                                <span class="inline-flex px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                            @if(count($member->skills) > 3)
                                                <span class="inline-flex px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                                    +{{ count($member->skills) - 3 }} more
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                        <button wire:click="$dispatch('member-details', { id: {{ $member->id }} })"
                                class="text-yellow-600 hover:text-yellow-800 text-sm font-medium inline-flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            View Details
                        </button>
                        
                        <div class="flex space-x-2">
                            <button wire:click="openRejectionModal({{ $member->id }})"
                                    class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-lg text-xs font-medium inline-flex items-center transition-colors">
                                <i class="fas fa-times mr-1"></i>
                                Reject
                            </button>
                            <button wire:click="openApprovalModal({{ $member->id }})"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs font-medium inline-flex items-center transition-colors">
                                <i class="fas fa-check mr-1"></i>
                                Approve
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($pendingMembers->hasPages())
            <div class="flex justify-center">
                {{ $pendingMembers->links() }}
            </div>
        @endif

        <!-- Select All Toggle -->
        @if($pendingMembers->count() > 1)
            <div class="flex justify-center">
                <button wire:click="toggleSelectAll" 
                        class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">
                    @if($selectAll)
                        <i class="fas fa-minus-square mr-1"></i>
                        Deselect All
                    @else
                        <i class="fas fa-check-square mr-1"></i>
                        Select All
                    @endif
                </button>
            </div>
        @endif

    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12">
            <div class="text-center">
                <div class="mx-auto h-24 w-24 text-gray-300 mb-6">
                    <i class="fas fa-clock text-6xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No pending applications</h3>
                <p class="text-gray-500 mb-6">All member applications have been reviewed and processed.</p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('members.index') }}" 
                       class="bg-primary-yellow hover:bg-yellow-400 text-black px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        View All Members
                    </a>
                    <a href="{{ route('members.create') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Member
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Approval Modal -->
    @if($showApprovalModal && $selectedMember)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Modal Header -->
                    <div class="bg-green-50 px-6 py-4 border-b border-green-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-green-900">Approve Member Application</h3>
                                <p class="text-sm text-green-700">{{ $selectedMember->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Institution:</span>
                                        <div class="text-gray-900">{{ $selectedMember->institution->name }}</div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Member Type:</span>
                                        <div class="text-gray-900">{{ ucfirst($selectedMember->member_type) }}</div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Email:</span>
                                        <div class="text-gray-900">{{ $selectedMember->user->email }}</div>
                                    </div>
                                    @if($selectedMember->student_id)
                                        <div>
                                            <span class="font-medium text-gray-700">Student ID:</span>
                                            <div class="text-gray-900">{{ $selectedMember->student_id }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Approval Notes (Optional)</label>
                                <textarea wire:model="approvalNotes"
                                          rows="3"
                                          class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm"
                                          placeholder="Add any notes about this approval..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button wire:click="closeModals"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Cancel
                        </button>
                        <button wire:click="approveMember"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i>
                            Approve Member
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Rejection Modal -->
    @if($showRejectionModal && $selectedMember)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Modal Header -->
                    <div class="bg-red-50 px-6 py-4 border-b border-red-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-red-900">Reject Member Application</h3>
                                <p class="text-sm text-red-700">{{ $selectedMember->user->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Institution:</span>
                                        <div class="text-gray-900">{{ $selectedMember->institution->name }}</div>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Member Type:</span>
                                        <div class="text-gray-900">{{ ucfirst($selectedMember->member_type) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="approvalNotes"
                                          rows="4"
                                          class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm"
                                          placeholder="Please provide a clear reason for rejection..."
                                          required></textarea>
                                @error('approvalNotes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button wire:click="closeModals"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Cancel
                        </button>
                        <button wire:click="rejectMember"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <i class="fas fa-times mr-2"></i>
                            Reject Application
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>


</div>

