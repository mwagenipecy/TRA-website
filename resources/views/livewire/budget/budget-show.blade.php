{{-- resources/views/livewire/budget/budget-show.blade.php --}}
<div>
    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Budget Details</h1>
            <div class="flex space-x-4">
                @if($budget->status === 'draft' && $budget->created_by === auth()->id())
                <button wire:click="submitForReview" 
                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-paper-plane mr-2"></i>Submit for Review
                </button>
                <a href="{{ route('budgets.edit', $budget->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit Budget
                </a>
                @endif
                <a href="{{ route('budgets.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Budgets
                </a>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- Budget Header --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $budget->title }}</h2>
                    <p class="text-gray-600 mt-2">{{ $budget->description }}</p>
                    
                    <div class="flex flex-wrap items-center gap-4 mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($budget->status === 'approved') bg-green-100 text-green-800
                            @elseif($budget->status === 'submitted') bg-yellow-100 text-yellow-800
                            @elseif($budget->status === 'under_review') bg-blue-100 text-blue-800
                            @elseif($budget->status === 'rejected') bg-red-100 text-red-800
                            @elseif($budget->status === 'revision_required') bg-orange-100 text-orange-800
                            @elseif($budget->status === 'draft') bg-gray-100 text-gray-800
                            @else bg-purple-100 text-purple-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $budget->status)) }}
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if($budget->type === 'yearly') bg-blue-100 text-blue-800
                            @elseif($budget->type === 'event') bg-green-100 text-green-800
                            @elseif($budget->type === 'project') bg-purple-100 text-purple-800
                            @elseif($budget->type === 'emergency') bg-red-100 text-red-800
                            @elseif($budget->type === 'equipment') bg-indigo-100 text-indigo-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($budget->type) }} Budget
                        </span>
                        
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($budget->priority_level <= 2) bg-red-100 text-red-800
                            @elseif($budget->priority_level == 3) bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            Priority: {{ $budget->priority_level }}
                        </span>

                        @if($budget->is_recurring)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-sync-alt mr-1"></i>Recurring
                        </span>
                        @endif
                    </div>
                </div>
                
                <div class="text-right ml-6">
                    <div class="text-sm text-gray-500">Budget Code</div>
                    <div class="text-lg font-semibold text-gray-800">{{ $budget->budget_code }}</div>
                    
                    <div class="text-sm text-gray-500 mt-2">Financial Year</div>
                    <div class="text-lg font-semibold text-gray-800">{{ $budget->financial_year }}</div>
                </div>
            </div>
        </div>

        {{-- Budget Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign text-2xl text-blue-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Requested Amount</p>
                        <p class="text-xl font-bold text-gray-900">TZS {{ number_format($budget->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
            
            @if($budget->approved_amount)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved Amount</p>
                        <p class="text-xl font-bold text-gray-900">TZS {{ number_format($budget->approved_amount, 2) }}</p>
                        @if($budget->approved_amount != $budget->total_amount)
                        <p class="text-xs text-gray-500">
                            Variance: TZS {{ number_format($budget->approved_amount - $budget->total_amount, 2) }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-credit-card text-2xl text-red-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Spent Amount</p>
                        <p class="text-xl font-bold text-gray-900">TZS {{ number_format($budget->spent_amount, 2) }}</p>
                        @if($budget->approved_amount > 0)
                        <p class="text-xs text-gray-500">
                            {{ number_format(($budget->spent_amount / $budget->approved_amount) * 100, 1) }}% utilized
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-piggy-bank text-2xl text-yellow-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Remaining</p>
                        <p class="text-xl font-bold text-gray-900">TZS {{ number_format($budget->remaining_amount, 2) }}</p>
                        @if($budget->approved_amount > 0)
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-yellow-500 h-2 rounded-full" 
                                 style="width: {{ min(($budget->remaining_amount / $budget->approved_amount) * 100, 100) }}%"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Budget Items --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                        <i class="fas fa-list text-yellow-500 mr-2"></i>Budget Items ({{ $budget->budgetItems->count() }})
                    </h3>
                    
                    @if($budget->budgetItems->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($budget->budgetItems as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                                        @if($item->description)
                                        <div class="text-sm text-gray-500 mt-1">{{ $item->description }}</div>
                                        @endif
                                        @if($item->justification)
                                        <div class="text-xs text-blue-600 mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>{{ $item->justification }}
                                        </div>
                                        @endif
                                        <div class="flex flex-wrap gap-1 mt-2">
                                            @if($item->is_mandatory)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Mandatory
                                            </span>
                                            @endif
                                            @if($item->is_approved)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check mr-1"></i>Approved
                                            </span>
                                            @endif
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                @if($item->priority <= 2) bg-red-100 text-red-800
                                                @elseif($item->priority == 3) bg-yellow-100 text-yellow-800
                                                @else bg-green-100 text-green-800 @endif">
                                                Priority {{ $item->priority }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($item->quantity) }}
                                        @if($item->unit_of_measure)
                                        <span class="text-gray-500">{{ $item->unit_of_measure }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        TZS {{ number_format($item->unit_cost, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        TZS {{ number_format($item->total_cost, 2) }}
                                        @if($item->approved_amount && $item->approved_amount != $item->total_cost)
                                        <div class="text-xs text-green-600 mt-1">
                                            <i class="fas fa-check mr-1"></i>Approved: TZS {{ number_format($item->approved_amount, 2) }}
                                        </div>
                                        @endif
                                        @if($item->approval_notes)
                                        <div class="text-xs text-blue-600 mt-1">
                                            <i class="fas fa-comment mr-1"></i>{{ $item->approval_notes }}
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Total:</td>
                                    <td class="px-6 py-3 text-sm font-bold text-gray-900">
                                        TZS {{ number_format($budget->budgetItems->sum('total_cost'), 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p>No budget items found.</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Budget Information Sidebar --}}
            <div class="space-y-6">
                {{-- Budget Details --}}
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                        <i class="fas fa-info-circle text-yellow-500 mr-2"></i>Budget Details
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <span class="text-sm font-medium text-gray-600">Created By:</span>
                            <div class="text-sm text-gray-900 mt-1">{{ $budget->creator->name }}</div>
                            <div class="text-xs text-gray-500">{{ $budget->creator->email }}</div>
                        </div>
                        
                        <div>
                            <span class="text-sm font-medium text-gray-600">Institution:</span>
                            <div class="text-sm text-gray-900 mt-1">{{ $budget->institution->name }}</div>
                            <div class="text-xs text-gray-500">{{ $budget->institution->code }}</div>
                        </div>
                        
                        <div>
                            <span class="text-sm font-medium text-gray-600">Budget Period:</span>
                            <div class="text-sm text-gray-900 mt-1">
                                {{ \Carbon\Carbon::parse($budget->start_date)->format('M d, Y') }} - 
                                {{ \Carbon\Carbon::parse($budget->end_date)->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($budget->start_date)->diffInDays(\Carbon\Carbon::parse($budget->end_date)) }} days
                            </div>
                        </div>
                        
                        <div>
                            <span class="text-sm font-medium text-gray-600">Created:</span>
                            <div class="text-sm text-gray-900 mt-1">{{ $budget->created_at->format('M d, Y H:i') }}</div>
                            <div class="text-xs text-gray-500">{{ $budget->created_at->diffForHumans() }}</div>
                        </div>
                        
                        @if($budget->is_recurring && $budget->recurrence_pattern)
                        <div>
                            <span class="text-sm font-medium text-gray-600">Recurrence:</span>
                            <div class="text-sm text-gray-900 mt-1">{{ $budget->recurrence_pattern }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Objectives --}}
                @if($budget->objectives && count($budget->objectives) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                        <i class="fas fa-bullseye text-yellow-500 mr-2"></i>Objectives
                    </h3>
                    
                    <ul class="space-y-3">
                        @foreach($budget->objectives as $index => $objective)
                        @if($objective)
                        <li class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-xs font-medium mr-3 mt-0.5">
                                {{ $index + 1 }}
                            </div>
                            <span class="text-sm text-gray-700">{{ $objective }}</span>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Review Information --}}
                @if($budget->status !== 'draft')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                        <i class="fas fa-clipboard-check text-yellow-500 mr-2"></i>Review Information
                    </h3>
                    
                    <div class="space-y-4">
                        @if($budget->reviewed_at)
                        <div>
                            <span class="text-sm font-medium text-gray-600">Reviewed By:</span>
                            <div class="text-sm text-gray-900 mt-1">{{ $budget->reviewer->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $budget->reviewed_at->format('M d, Y H:i') }}</div>
                        </div>
                        @endif
                        
                        @if($budget->approved_at)
                        <div>
                            <span class="text-sm font-medium text-gray-600">Approved By:</span>
                            <div class="text-sm text-gray-900 mt-1">{{ $budget->approver->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $budget->approved_at->format('M d, Y H:i') }}</div>
                        </div>
                        @endif
                        
                        @if($budget->review_comments)
                        <div>
                            <span class="text-sm font-medium text-gray-600">Review Comments:</span>
                            <div class="text-sm text-gray-700 bg-blue-50 border border-blue-200 rounded-lg p-3 mt-2">
                                <i class="fas fa-comment text-blue-500 mr-2"></i>{{ $budget->review_comments }}
                            </div>
                        </div>
                        @endif
                        
                        @if($budget->rejection_reason)
                        <div>
                            <span class="text-sm font-medium text-gray-600">Rejection Reason:</span>
                            <div class="text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg p-3 mt-2">
                                <i class="fas fa-times-circle text-red-500 mr-2"></i>{{ $budget->rejection_reason }}
                            </div>
                        </div>
                        @endif

                        @if($budget->revision_notes)
                        <div>
                            <span class="text-sm font-medium text-gray-600">Revision Notes:</span>
                            <div class="text-sm text-orange-700 bg-orange-50 border border-orange-200 rounded-lg p-3 mt-2">
                                <i class="fas fa-edit text-orange-500 mr-2"></i>
                                @if(is_array($budget->revision_notes))
                                    {{ implode(', ', $budget->revision_notes) }}
                                @else
                                    {{ $budget->revision_notes }}
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Attachments --}}
                @if($budget->attachments && count($budget->attachments) > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                        <i class="fas fa-paperclip text-yellow-500 mr-2"></i>Attachments
                    </h3>
                    
                    <div class="space-y-2">
                        @foreach($budget->attachments as $attachment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-file text-gray-400 mr-3"></i>
                                <span class="text-sm text-gray-700">{{ $attachment['name'] ?? 'Attachment' }}</span>
                            </div>
                            <a href="{{ $attachment['path'] ?? '#' }}" 
                               class="text-yellow-600 hover:text-yellow-700 text-sm">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>