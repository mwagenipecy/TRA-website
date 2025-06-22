<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Pending Budget Approvals</h1>
        <div class="text-sm text-gray-600">
            <i class="fas fa-clock mr-2"></i>{{ $budgets->total() }} budgets pending review
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <input type="text" wire:model="search" placeholder="Search pending budgets..."
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
    </div>

    {{-- Pending Budgets List --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institution</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($budgets as $budget)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $budget->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $budget->budget_code }}</div>
                                    <div class="flex items-center mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($budget->type === 'yearly') bg-blue-100 text-blue-800
                                            @elseif($budget->type === 'event') bg-green-100 text-green-800
                                            @elseif($budget->type === 'project') bg-purple-100 text-purple-800
                                            @elseif($budget->type === 'emergency') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($budget->type) }}
                                        </span>
                                        <span class="ml-2 text-xs text-gray-500">FY {{ $budget->financial_year }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $budget->institution->name }}</div>
                            <div class="text-sm text-gray-500">{{ $budget->institution->code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">TZS {{ number_format($budget->total_amount) }}</div>
                            <div class="text-sm text-gray-500">{{ $budget->budgetItems->count() }} items</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $budget->created_at->format('M d, Y') }}</div>
                            <div class="text-sm text-gray-500">by {{ $budget->creator->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('budgets.show', $budget->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button wire:click="selectBudget({{ $budget->id }}, 'approve')" 
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button wire:click="selectBudget({{ $budget->id }}, 'revision')" 
                                        class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="selectBudget({{ $budget->id }}, 'reject')" 
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No pending budgets found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $budgets->links() }}
        </div>
    </div>

    {{-- Review Modal --}}
    @if($selectedBudget)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ ucfirst($action) }} Budget: {{ $selectedBudget->title }}
                            </h3>
                            
                            <div class="mt-4">
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="font-medium">Budget Code:</span> {{ $selectedBudget->budget_code }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Type:</span> {{ ucfirst($selectedBudget->type) }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Amount:</span> TZS {{ number_format($selectedBudget->total_amount) }}
                                        </div>
                                        <div>
                                            <span class="font-medium">Items:</span> {{ $selectedBudget->budgetItems->count() }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if($action === 'approve')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Approved Amount (TZS)</label>
                                    <input type="number" wire:model="approvedAmount" min="0" step="0.01"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                                    @error('approvedAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                @endif
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $action === 'approve' ? 'Approval Notes' : ($action === 'reject' ? 'Rejection Reason' : 'Revision Notes') }}
                                    </label>
                                    <textarea wire:model="reviewComments" rows="4" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500"></textarea>
                                    @error('reviewComments') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="processReview" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white 
                            @if($action === 'approve') bg-green-600 hover:bg-green-700
                            @elseif($action === 'reject') bg-red-600 hover:bg-red-700
                            @else bg-yellow-600 hover:bg-yellow-700 @endif
                            focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ ucfirst($action) }} Budget
                    </button>
                    <button type="button" wire:click="$set('selectedBudget', null)" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>