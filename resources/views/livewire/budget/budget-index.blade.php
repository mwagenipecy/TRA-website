<div>

{{-- resources/views/livewire/budget/budget-index.blade.php --}}
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Budget Management</h1>
        @if(in_array(auth()->user()->role, ['leader', 'supervisor']))
        <a href="{{ route('budgets.create') }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2 rounded-lg font-semibold transition duration-200">
            <i class="fas fa-plus mr-2"></i>Create Budget
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <input type="text" wire:model="search" placeholder="Search budgets..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            <div>
                <select wire:model="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="submitted">Submitted</option>
                    <option value="under_review">Under Review</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <select wire:model="typeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Types</option>
                    <option value="yearly">Yearly</option>
                    <option value="event">Event</option>
                    <option value="project">Project</option>
                    <option value="emergency">Emergency</option>
                    <option value="equipment">Equipment</option>
                </select>
            </div>
            <div>
                <select wire:model="yearFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role === 'tra_officer')
            <div>
                <select wire:model="institutionFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
    </div>

    {{-- Budget List --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($budgets as $budget)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $budget->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $budget->budget_code }}</div>
                                    @if(auth()->user()->role === 'tra_officer')
                                    <div class="text-xs text-gray-400">{{ $budget->institution->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($budget->type === 'yearly') bg-blue-100 text-blue-800
                                @elseif($budget->type === 'event') bg-green-100 text-green-800
                                @elseif($budget->type === 'project') bg-purple-100 text-purple-800
                                @elseif($budget->type === 'emergency') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($budget->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>Allocated: TZS {{ number_format($budget->approved_amount ?? $budget->total_amount) }}</div>
                            @if($budget->approved_amount)
                            <div class="text-xs text-gray-500">
                                Spent: TZS {{ number_format($budget->spent_amount) }} | 
                                Remaining: TZS {{ number_format($budget->remaining_amount) }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($budget->status === 'approved') bg-green-100 text-green-800
                                @elseif($budget->status === 'submitted') bg-yellow-100 text-yellow-800
                                @elseif($budget->status === 'rejected') bg-red-100 text-red-800
                                @elseif($budget->status === 'draft') bg-gray-100 text-gray-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $budget->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>FY {{ $budget->financial_year }}</div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($budget->start_date)->format('M d') }} - 
                                {{ \Carbon\Carbon::parse($budget->end_date)->format('M d') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('budgets.show', $budget->id) }}" 
                                   class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($budget->created_by === auth()->id() && $budget->status === 'draft')
                                <a href="{{ route('budgets.edit', $budget->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button wire:click="deleteBudget({{ $budget->id }})" 
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No budgets found.
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
</div>

</div>
