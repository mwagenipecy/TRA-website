<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AuditLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedUser = '';
    public $selectedEvent = '';
    public $selectedSubjectType = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $showDetailsModal = false;
    public $selectedLog = null;
    public $showExportModal = false;
    public $exportFormat = 'csv';
    public $exportDateFrom = '';
    public $exportDateTo = '';
    
    // Available filters
    public $events = [
        'created' => 'Created',
        'updated' => 'Updated',
        'deleted' => 'Deleted',
        'login' => 'Login',
        'logout' => 'Logout',
        'password_changed' => 'Password Changed',
        'role_assigned' => 'Role Assigned',
        'role_revoked' => 'Role Revoked',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'suspended' => 'Suspended',
        'activated' => 'Activated',
        'exported' => 'Exported',
        'imported' => 'Imported',
    ];

    public $subjectTypes = [
        'App\\Models\\User' => 'Users',
        'App\\Models\\Role' => 'Roles',
        'App\\Models\\Permission' => 'Permissions',
        'App\\Models\\Institution' => 'Institutions',
        'App\\Models\\Event' => 'Events',
        'App\\Models\\Budget' => 'Budgets',
        'App\\Models\\Certificate' => 'Certificates',
        'App\\Models\\Member' => 'Members',
    ];

    public function mount()
    {
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedUser()
    {
        $this->resetPage();
    }

    public function updatingSelectedEvent()
    {
        $this->resetPage();
    }

    public function updatingSelectedSubjectType()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'selectedUser', 'selectedEvent', 'selectedSubjectType', 'dateFrom', 'dateTo']);
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->resetPage();
    }

    public function showDetails($logId)
    {
        $this->selectedLog = Activity::with(['causer', 'subject', 'institution'])->findOrFail($logId);
        $this->showDetailsModal = true;
    }

    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->showExportModal = false;
        $this->selectedLog = null;
    }

    public function showExport()
    {
        $this->exportDateFrom = $this->dateFrom;
        $this->exportDateTo = $this->dateTo;
        $this->showExportModal = true;
    }

    public function exportLogs()
    {
        $this->validate([
            'exportDateFrom' => 'required|date',
            'exportDateTo' => 'required|date|after_or_equal:exportDateFrom',
            'exportFormat' => 'required|in:csv,xlsx,pdf',
        ]);

        // Build query for export
        $query = $this->getLogsQuery();
        
        if ($this->exportDateFrom) {
            $query->whereDate('created_at', '>=', $this->exportDateFrom);
        }
        
        if ($this->exportDateTo) {
            $query->whereDate('created_at', '<=', $this->exportDateTo);
        }

        $logs = $query->get();

        // Record export activity
        Activity::log(
            'export',
            'Audit logs exported',
            null,
            null,
            [
                'format' => $this->exportFormat,
                'date_from' => $this->exportDateFrom,
                'date_to' => $this->exportDateTo,
                'records_count' => $logs->count(),
            ]
        );

        // Generate filename
        $filename = 'audit_logs_' . $this->exportDateFrom . '_to_' . $this->exportDateTo . '.' . $this->exportFormat;

        // Handle different export formats
        switch ($this->exportFormat) {
            case 'csv':
                return $this->exportToCsv($logs, $filename);
            case 'xlsx':
                return $this->exportToExcel($logs, $filename);
            case 'pdf':
                return $this->exportToPdf($logs, $filename);
        }

        $this->closeModal();
        session()->flash('message', 'Export completed successfully!');
    }

    private function exportToCsv($logs, $filename)
    {
        $csvData = "ID,User,Event,Subject,Description,Properties,IP Address,User Agent,Date\n";
        
        foreach ($logs as $log) {
            $csvData .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $log->id,
                $log->user ? $log->user->name : 'System',
                $log->type ?? '',
                $log->subject_type ?? '',
                str_replace('"', '""', $log->description),
                str_replace('"', '""', json_encode($log->properties ?? [])),
                $log->ip_address ?? '',
                str_replace('"', '""', $log->user_agent ?? ''),
                $log->performed_at->format('Y-m-d H:i:s')
            );
        }

        return response()->streamDownload(function() use ($csvData) {
            echo $csvData;
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportToExcel($logs, $filename)
    {
        // This would require a package like PhpSpreadsheet
        // For now, return CSV format
        return $this->exportToCsv($logs, str_replace('.xlsx', '.csv', $filename));
    }

    private function exportToPdf($logs, $filename)
    {
        // This would require a package like DomPDF or TCPDF
        // For now, return CSV format
        return $this->exportToCsv($logs, str_replace('.pdf', '.csv', $filename));
    }

    public function deleteOldLogs($days = 90)
    {
        $deletedCount = Activity::where('performed_at', '<', Carbon::now()->subDays($days))->delete();
        
        Activity::log(
            'cleanup',
            'Old audit logs deleted',
            null,
            null,
            ['days' => $days, 'deleted_count' => $deletedCount]
        );

        session()->flash('message', "Deleted {$deletedCount} old audit logs (older than {$days} days).");
    }

    private function getLogsQuery()
    {
        return Activity::query()
            ->with(['causer', 'subject', 'institution'])
            ->when($this->search, function (Builder $query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%')
                      ->orWhereHas('causer', function ($userQuery) {
                          $userQuery->where('name', 'like', '%' . $this->search . '%')
                                   ->orWhere('email', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->selectedUser, function (Builder $query) {
                $query->where('user_id', $this->selectedUser);
            })
            ->when($this->selectedEvent, function (Builder $query) {
                $query->where('type', $this->selectedEvent);
            })
            ->when($this->selectedSubjectType, function (Builder $query) {
                $query->where('subject_type', $this->selectedSubjectType);
            })
            ->when($this->dateFrom, function (Builder $query) {
                $query->whereDate('performed_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function (Builder $query) {
                $query->whereDate('performed_at', '<=', $this->dateTo);
            });
    }

    public function render()
    {
        $logs = $this->getLogsQuery()
            ->latest()
            ->paginate(20);

        $users = User::where('status', 'active')->get();

        // Get statistics
        $todayLogs = Activity::whereDate('performed_at', Carbon::today())->count();
        $weekLogs = Activity::where('performed_at', '>=', Carbon::now()->subWeek())->count();
        $monthLogs = Activity::where('performed_at', '>=', Carbon::now()->subMonth())->count();
        
        // Get most active users
        $mostActiveUsers = Activity::selectRaw('user_id, COUNT(*) as activity_count')
            ->with('causer')
            ->where('performed_at', '>=', Carbon::now()->subDays(30))
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->orderByDesc('activity_count')
            ->limit(5)
            ->get();

        // Get activity by event type
        $eventStats = Activity::selectRaw('type, COUNT(*) as count')
            ->where('performed_at', '>=', Carbon::now()->subDays(30))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('livewire.admin.audit-logs', compact(
            'logs', 'users', 'todayLogs', 'weekLogs', 'monthLogs', 'mostActiveUsers', 'eventStats'
        ));
    }
}