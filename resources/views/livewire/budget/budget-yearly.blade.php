<div>
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Yearly Budget Analysis</h1>
        <div class="flex items-center space-x-4">
            <select wire:model="selectedYear" 
                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-invoice-dollar text-3xl text-yellow-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Budgets</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $yearlyData['total_budgets'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-coins text-3xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Allocated</p>
                    <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($yearlyData['total_spent'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-piggy-bank text-3xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Remaining</p>
                    <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($yearlyData['total_remaining'] ?? 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Category Breakdown --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-chart-pie text-yellow-500 mr-2"></i>Budget by Category
            </h2>
            
            @if(count($categoryBreakdown) > 0)
            <div class="space-y-4">
                @foreach($categoryBreakdown as $category)
                @php
                    $percentage = ($yearlyData['total_allocated'] > 0) ? ($category['total_amount'] / $yearlyData['total_allocated']) * 100 : 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $category['category'] }}</span>
                            <span class="text-sm text-gray-500">{{ number_format($percentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <div class="text-sm font-semibold text-gray-900">TZS {{ number_format($category['total_amount']) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-chart-pie text-4xl mb-4"></i>
                <p>No budget data available for {{ $selectedYear }}</p>
            </div>
            @endif
        </div>

        {{-- Institution Comparison (TRA Officers only) --}}
        @if(auth()->user()->role === 'tra_officer' && count($institutionComparison) > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-university text-yellow-500 mr-2"></i>Institution Comparison
            </h2>
            
            <div class="space-y-4">
                @foreach($institutionComparison as $institution)
                @php
                    $percentage = ($yearlyData['total_allocated'] > 0) ? ($institution['total_amount'] / $yearlyData['total_allocated']) * 100 : 0;
                @endphp
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $institution['institution']['name'] }}</span>
                            <span class="text-sm text-gray-500">{{ number_format($percentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    <div class="ml-4 text-right">
                        <div class="text-sm font-semibold text-gray-900">TZS {{ number_format($institution['total_amount']) }}</div>
                        <div class="text-xs text-gray-500">{{ $institution['institution']['code'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Budget Performance --}}
        <div class="bg-white rounded-lg shadow-md p-6 {{ auth()->user()->role !== 'tra_officer' ? 'lg:col-span-2' : '' }}">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b border-yellow-200 pb-2">
                <i class="fas fa-chart-line text-yellow-500 mr-2"></i>Budget Performance
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">
                        {{ number_format($yearlyData['completion_rate'] ?? 0, 1) }}%
                    </div>
                    <div class="text-sm text-gray-600 mt-1">Approval Rate</div>
                </div>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">
                        TZS {{ number_format($yearlyData['avg_budget_size'] ?? 0) }}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">Average Budget Size</div>
                </div>
                
                <div class="text-center">
                    @php
                        $utilizationRate = ($yearlyData['total_allocated'] > 0) ? 
                            ($yearlyData['total_spent'] / $yearlyData['total_allocated']) * 100 : 0;
                    @endphp
                    <div class="text-3xl font-bold text-purple-600">
                        {{ number_format($utilizationRate, 1) }}%
                    </div>
                    <div class="text-sm text-gray-600 mt-1">Utilization Rate</div>
                </div>
            </div>
            
            {{-- Progress Bar for Utilization --}}
            <div class="mt-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Overall Budget Utilization</span>
                    <span class="text-sm text-gray-500">{{ number_format($utilizationRate, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-500 to-yellow-500 h-3 rounded-full" 
                         style="width: {{ min($utilizationRate, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
