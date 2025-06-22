<div class="min-h-screen bg-gray-900">
    <!-- Header -->
    <div class="bg-black border-b border-yellow-500">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-yellow-400">Audit Logs</h1>
                    <p class="text-gray-400 mt-1">Monitor system activities and user actions</p>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="showExport" 
                            class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Export
                    </button>
                    <button wire:click="$refresh" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="bg-gray-800 border-b border-gray-700 px-6 py-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-black text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ $todayLogs }}</div>
                        <div class="text-gray-400 text-sm">Today's Activity</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ $weekLogs }}</div>
                        <div class="text-gray-400 text-sm">This Week</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ $monthLogs }}</div>
                        <div class="text-gray-400 text-sm">This Month</div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ $logs->total() }}</div>
                        <div class="text-gray-400 text-sm">Total Filtered</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-800 border-b border-gray-700 px-6 py-4">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <input wire:model.live="search" 
                       type="text" 
                       placeholder="Search logs..." 
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-yellow-500">
            </div>
            <div>
                <select wire:model.live="selectedUser" 
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="selectedEvent" 
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    <option value="">All Events</option>
                    @foreach ($events as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select wire:model.live="selectedSubjectType" 
                        class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                    <option value="">All Subjects</option>
                    @foreach ($subjectTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input wire:model.live="dateFrom" 
                       type="date" 
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
            </div>
            <div class="flex space-x-2">
                <input wire:model.live="dateTo" 
                       type="date" 
                       class="flex-1 bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                <button wire:click="clearFilters" 
                        class="bg-gray-600 hover:bg-gray-500 text-white px-3 py-2 rounded-lg transition-colors duration-200"
                        title="Clear Filters">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="bg-yellow-500 text-black px-6 py-3 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Audit Logs Table -->
    <div class="px-6 py-6">
        <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black">
                        <tr>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">User</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Event</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Subject</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Description</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">IP Address</th>
                            <th class="px-6 py-4 text-left text-yellow-400 font-medium">Date</th>
                            <th class="px-6 py-4 text-center text-yellow-400 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($log->causer)
                                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-black font-bold text-sm mr-3">
                                                {{ strtoupper(substr($log->causer->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-white font-medium text-sm">{{ $log->causer->name }}</div>
                                                <div class="text-gray-400 text-xs">{{ $log->causer->email }}</div>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-gray-300 font-bold text-sm mr-3">
                                                <i class="fas fa-robot"></i>
                                            </div>
                                            <div>
                                                <div class="text-gray-300 font-medium text-sm">System</div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->event)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                            @switch($log->event)
                                                @case('created')
                                                    bg-green-900 text-green-300
                                                    @break
                                                @case('updated')
                                                    bg-blue-900 text-blue-300
                                                    @break
                                                @case('deleted')
                                                    bg-red-900 text-red-300
                                                    @break
                                                @case('login')
                                                    bg-yellow-900 text-yellow-300
                                                    @break
                                                @case('logout')
                                                    bg-gray-700 text-gray-300
                                                    @break
                                                @default
                                                    bg-purple-900 text-purple-300
                                            @endswitch">
                                            {{ $events[$log->event] ?? ucfirst($log->event) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($log->subject_type)
                                        <div class="text-white text-sm">
                                            {{ $subjectTypes[$log->subject_type] ?? class_basename($log->subject_type) }}
                                        </div>
                                        @if ($log->subject_id)
                                            <div class="text-gray-400 text-xs">ID: {{ $log->subject_id }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-300 text-sm">
                                        {{ Str::limit($log->description, 50) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-400 text-sm">
                                        {{ $log->getExtraProperty('ip_address') ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-300 text-sm">
                                        {{ $log->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-gray-400 text-xs">
                                        {{ $log->created_at->format('H:i:s') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center">
                                        <button wire:click="showDetails({{ $log->id }})" 
                                                class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                                                title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fas fa-history text-4xl mb-4"></i>
                                    <p class="text-lg">No audit logs found</p>
                                    <p class="text-sm">Try adjusting your filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->links() }}
        </div>

        <!-- Cleanup Section -->
        <div class="mt-8 bg-gray-800 rounded-lg border border-gray-700 p-6">
            <h3 class="text-lg font-bold text-yellow-400 mb-4">Log Cleanup</h3>
            <p class="text-gray-300 mb-4">Remove old audit logs to free up storage space and improve performance.</p>
            <div class="flex space-x-3">
                <button wire:click="deleteOldLogs(30)" 
                        class="bg-orange-600 hover:bg-orange-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Delete logs older than 30 days
                </button>
                <button wire:click="deleteOldLogs(90)" 
                        class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Delete logs older than 90 days
                </button>
                <button wire:click="deleteOldLogs(365)" 
                        class="bg-red-800 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Delete logs older than 1 year
                </button>
            </div>
        </div>
    </div>

    <!-- Log Details Modal -->
    @if ($showDetailsModal && $selectedLog)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-yellow-400">Audit Log Details</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-3">Basic Information</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-400 text-sm">Log ID</label>
                                <div class="text-white">{{ $selectedLog->id }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm">User</label>
                                <div class="text-white">
                                    {{ $selectedLog->causer ? $selectedLog->causer->name . ' (' . $selectedLog->causer->email . ')' : 'System' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm">Event</label>
                                <div class="text-white">{{ $selectedLog->event ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm">Subject Type</label>
                                <div class="text-white">{{ $selectedLog->subject_type ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm">Subject ID</label>
                                <div class="text-white">{{ $selectedLog->subject_id ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm">Date & Time</label>
                                <div class="text-white">{{ $selectedLog->created_at->format('M d, Y H:i:s') }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-white mb-3">Technical Details</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-gray-400 text-sm">IP Address</label>
                                <div class="text-white">{{ $selectedLog->getExtraProperty('ip_address') ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm">User Agent</label>
                                <div class="text-white text-sm break-all">{{ $selectedLog->getExtraProperty('user_agent') ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm">Batch UUID</label>
                                <div class="text-white text-sm">{{ $selectedLog->batch_uuid ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <h4 class="text-lg font-semibold text-white mb-3">Description</h4>
                        <div class="bg-gray-700 rounded-lg p-4 text-white">
                            {{ $selectedLog->description }}
                        </div>
                    </div>

                    @if ($selectedLog->properties && count($selectedLog->properties) > 0)
                        <div class="md:col-span-2">
                            <h4 class="text-lg font-semibold text-white mb-3">Properties</h4>
                            <div class="bg-gray-700 rounded-lg p-4">
                                <pre class="text-gray-300 text-sm overflow-x-auto">{{ json_encode($selectedLog->properties, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end mt-6">
                    <button wire:click="closeModal" 
                            class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Export Modal -->
    @if ($showExportModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md border border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-yellow-400">Export Audit Logs</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-white">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit="exportLogs">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Export Format *</label>
                            <select wire:model="exportFormat" 
                                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                                <option value="csv">CSV</option>
                                <option value="xlsx">Excel (XLSX)</option>
                                <option value="pdf">PDF</option>
                            </select>
                            @error('exportFormat') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Date From *</label>
                            <input wire:model="exportDateFrom" type="date" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('exportDateFrom') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Date To *</label>
                            <input wire:model="exportDateTo" type="date" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500">
                            @error('exportDateTo') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="closeModal" 
                                class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            Export
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Activity Statistics Sidebar -->
    <div class="fixed right-0 top-20 w-80 h-[calc(100vh-5rem)] bg-gray-800 border-l border-gray-700 p-6 overflow-y-auto transform translate-x-full hover:translate-x-0 transition-transform duration-300 z-40">
        <h3 class="text-lg font-bold text-yellow-400 mb-6">Activity Statistics</h3>
        
        <!-- Most Active Users -->
        <div class="mb-8">
            <h4 class="text-md font-semibold text-white mb-4">Most Active Users (30 days)</h4>
            <div class="space-y-3">
                @foreach ($mostActiveUsers as $user)
                    @if ($user->causer)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-black font-bold text-sm mr-3">
                                    {{ strtoupper(substr($user->causer->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-white text-sm">{{ $user->causer->name }}</div>
                                    <div class="text-gray-400 text-xs">{{ $user->causer->email }}</div>
                                </div>
                            </div>
                            <div class="text-yellow-400 font-bold">{{ $user->activity_count }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Event Statistics -->
        <div>
            <h4 class="text-md font-semibold text-white mb-4">Top Events (30 days)</h4>
            <div class="space-y-3">
                @foreach ($eventStats as $stat)
                    <div class="flex items-center justify-between">
                        <div class="text-gray-300 text-sm">{{ $events[$stat->event] ?? ucfirst($stat->event) }}</div>
                        <div class="text-yellow-400 font-bold">{{ $stat->count }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 pt-6 border-t border-gray-700">
            <h4 class="text-md font-semibold text-white mb-4">Quick Actions</h4>
            <div class="space-y-2">
                <button wire:click="clearFilters" 
                        class="w-full bg-gray-700 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm transition-colors duration-200">
                    Clear All Filters
                </button>
                <button wire:click="showExport" 
                        class="w-full bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 rounded text-sm transition-colors duration-200">
                    Export Current View
                </button>
                <button wire:click="deleteOldLogs(90)" 
                        class="w-full bg-red-600 hover:bg-red-500 text-white px-3 py-2 rounded text-sm transition-colors duration-200"
                        onclick="return confirm('Are you sure you want to delete logs older than 90 days?')">
                    Cleanup Old Logs
                </button>
            </div>
        </div>

        <!-- Hover Indicator -->
        <div class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-full bg-yellow-500 text-black px-2 py-1 rounded-l text-xs font-bold">
            <i class="fas fa-chart-bar"></i>
        </div>
    </div>
</div>