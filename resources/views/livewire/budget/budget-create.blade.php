<div>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Budget</h1>
        <a href="{{ route('budgets.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Back to Budgets
        </a>
    </div>

    <form wire:submit.prevent="saveBudget">
        {{-- Basic Information --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-info-circle text-yellow-500 mr-2"></i>Basic Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget Title *</label>
                    <input type="text" wire:model="title" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget Type *</label>
                    <select wire:model="type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="yearly">Yearly Budget</option>
                        <option value="event">Event Budget</option>
                        <option value="project">Project Budget</option>
                        <option value="emergency">Emergency Budget</option>
                        <option value="equipment">Equipment Budget</option>
                    </select>
                    @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Financial Year *</label>
                    <input type="number" wire:model="financial_year" min="2020" max="2030"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    @error('financial_year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                    <select wire:model="priority_level" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="1">Very High</option>
                        <option value="2">High</option>
                        <option value="3">Medium</option>
                        <option value="4">Low</option>
                        <option value="5">Very Low</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" wire:model="start_date" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                    <input type="date" wire:model="end_date" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea wire:model="description" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" wire:model="is_recurring" class="form-checkbox h-4 w-4 text-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">This is a recurring budget</span>
                </label>
                @if($is_recurring)
                <div class="mt-2">
                    <input type="text" wire:model="recurrence_pattern" placeholder="e.g., Monthly, Quarterly, Yearly"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                @endif
            </div>
        </div>

        {{-- Objectives --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-bullseye text-yellow-500 mr-2"></i>Budget Objectives
            </h2>
            
            @foreach($objectives as $index => $objective)
            <div class="flex items-center mb-3">
                <input type="text" wire:model="objectives.{{ $index }}" placeholder="Enter objective..."
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <button type="button" wire:click="removeObjective({{ $index }})"
                        class="ml-2 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            @endforeach
            
            <button type="button" wire:click="addObjective"
                    class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-semibold">
                <i class="fas fa-plus mr-2"></i>Add Objective
            </button>
        </div>

        {{-- Budget Items --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-list text-yellow-500 mr-2"></i>Budget Items
            </h2>
            
            {{-- Add New Item Form --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-medium text-gray-800 mb-3">Add New Budget Item</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select wire:model="newItem.category" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                            <option value="">Select Category</option>
                            @foreach($categories as $key => $category)
                                <option value="{{ $key }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                        <input type="text" wire:model="newItem.item_name" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                        <input type="number" wire:model="newItem.quantity" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost (TZS) *</label>
                        <input type="number" wire:model="newItem.unit_cost" min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure</label>
                        <input type="text" wire:model="newItem.unit_of_measure" placeholder="e.g., pieces, hours"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="button" wire:click="addBudgetItem"
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-semibold text-sm">
                            Add Item
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea wire:model="newItem.description" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Justification</label>
                        <textarea wire:model="newItem.justification" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"></textarea>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4 mt-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="newItem.is_mandatory" class="form-checkbox h-4 w-4 text-yellow-500">
                        <span class="ml-2 text-sm text-gray-700">Mandatory Item</span>
                    </label>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select wire:model="newItem.priority" 
                                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                            <option value="1">Very High</option>
                            <option value="2">High</option>
                            <option value="3">Medium</option>
                            <option value="4">Low</option>
                            <option value="5">Very Low</option>
                        </select>
                    </div>
                </div>
            </div>
            
            {{-- Budget Items Table --}}
            @if(count($budgetItems) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($budgetItems as $index => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item['item_name'] }}</div>
                                @if($item['description'])
                                <div class="text-sm text-gray-500">{{ $item['description'] }}</div>
                                @endif
                                <div class="flex space-x-2 mt-1">
                                    @if($item['is_mandatory'])
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        Mandatory
                                    </span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        @if($item['priority'] <= 2) bg-red-100 text-red-800
                                        @elseif($item['priority'] == 3) bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        Priority {{ $item['priority'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['category'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['quantity'] }} {{ $item['unit_of_measure'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                TZS {{ number_format($item['unit_cost']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                TZS {{ number_format($item['total_cost']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button type="button" wire:click="removeBudgetItem({{ $index }})"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 bg-yellow-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-800">Total Budget Amount:</span>
                    <span class="text-xl font-bold text-yellow-600">TZS {{ number_format($total_amount) }}</span>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4"></i>
                <p>No budget items added yet. Add items above to get started.</p>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-end space-x-4">
                <a href="{{ route('budgets.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-3 rounded-lg font-semibold transition duration-200">
                    <i class="fas fa-save mr-2"></i>Create Budget
                </button>
            </div>
        </div>
    </form>
</div>

</div>
