<?php

namespace App\Livewire\Institutions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Institution;
use App\Models\Activity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;

class Approval extends Component
{
    use WithPagination, AuthorizesRequests;

    public $search = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    // For bulk actions
    public $selectedInstitutions = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'typeFilter' => ['except' => 'all'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
       // $this->authorize('manage-institutions');
    }

    public function render()
    {
        return view('livewire.institutions.approval', [
            'institutions' => $this->institutions,
            'stats' => $this->getStats(),
        ]);
    }

    #[Computed]
    public function institutions()
    {
        return Institution::query()
            ->with(['creator'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%')
                      ->orWhere('region', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter !== 'all', function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedInstitutions = $this->institutions->pluck('id')->toArray();
        } else {
            $this->selectedInstitutions = [];
        }
    }

    public function updatedSelectedInstitutions()
    {
        $this->selectAll = count($this->selectedInstitutions) === $this->institutions->count();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function approveInstitution($institutionId)
    {
      //  $this->authorize('manage-institutions');

        $institution = Institution::findOrFail($institutionId);

        if ($institution->status !== 'pending') {
            session()->flash('error', 'Only pending institutions can be approved.');
            return;
        }

        $institution->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Activity::log(
            'institution_approved',
            "Institution '{$institution->name}' was approved",
            auth()->user(),
            $institution
        );

        session()->flash('success', "Institution '{$institution->name}' has been approved successfully.");
        
        // Remove from selected if it was selected
        $this->selectedInstitutions = array_diff($this->selectedInstitutions, [$institutionId]);
        
        // Clear computed property cache
        unset($this->institutions);
    }

    public function rejectInstitution($institutionId)
    {
       // $this->authorize('manage-institutions');

        $institution = Institution::findOrFail($institutionId);

        if ($institution->status !== 'pending') {
            session()->flash('error', 'Only pending institutions can be rejected.');
            return;
        }

        $institution->update([
            'status' => 'inactive',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Activity::log(
            'institution_rejected',
            "Institution '{$institution->name}' was rejected",
            auth()->user(),
            $institution
        );

        session()->flash('warning', "Institution '{$institution->name}' has been rejected.");
        
        // Remove from selected if it was selected
        $this->selectedInstitutions = array_diff($this->selectedInstitutions, [$institutionId]);
        
        // Clear computed property cache
        unset($this->institutions);
    }

    public function suspendInstitution($institutionId)
    {
        $this->authorize('manage-institutions');

        $institution = Institution::findOrFail($institutionId);

        $institution->update(['status' => 'suspended']);

        Activity::log(
            'institution_suspended',
            "Institution '{$institution->name}' was suspended",
            auth()->user(),
            $institution
        );

        session()->flash('warning', "Institution '{$institution->name}' has been suspended.");
        
        // Clear computed property cache
        unset($this->institutions);
    }

    public function activateInstitution($institutionId)
    {
        $this->authorize('manage-institutions');

        $institution = Institution::findOrFail($institutionId);

        $institution->update(['status' => 'active']);

        Activity::log(
            'institution_activated',
            "Institution '{$institution->name}' was activated",
            auth()->user(),
            $institution
        );

        session()->flash('success', "Institution '{$institution->name}' has been activated.");
        
        // Clear computed property cache
        unset($this->institutions);
    }

    public function bulkApprove()
    {
        $this->authorize('manage-institutions');

        if (empty($this->selectedInstitutions)) {
            session()->flash('error', 'Please select institutions to approve.');
            return;
        }

        $institutions = Institution::whereIn('id', $this->selectedInstitutions)
            ->where('status', 'pending')
            ->get();

        $approvedCount = 0;
        foreach ($institutions as $institution) {
            $institution->update([
                'status' => 'active',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            Activity::log(
                'institution_approved',
                "Institution '{$institution->name}' was approved (bulk action)",
                auth()->user(),
                $institution
            );

            $approvedCount++;
        }

        session()->flash('success', "{$approvedCount} institutions have been approved successfully.");
        
        $this->selectedInstitutions = [];
        $this->selectAll = false;
        
        // Clear computed property cache
        unset($this->institutions);
    }

    public function bulkReject()
    {
        $this->authorize('manage-institutions');

        if (empty($this->selectedInstitutions)) {
            session()->flash('error', 'Please select institutions to reject.');
            return;
        }

        $institutions = Institution::whereIn('id', $this->selectedInstitutions)
            ->where('status', 'pending')
            ->get();

        $rejectedCount = 0;
        foreach ($institutions as $institution) {
            $institution->update([
                'status' => 'inactive',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            Activity::log(
                'institution_rejected',
                "Institution '{$institution->name}' was rejected (bulk action)",
                auth()->user(),
                $institution
            );

            $rejectedCount++;
        }

        session()->flash('warning', "{$rejectedCount} institutions have been rejected.");
        
        $this->selectedInstitutions = [];
        $this->selectAll = false;
        
        // Clear computed property cache
        unset($this->institutions);
    }

    private function getStats()
    {
        return [
            'total' => Institution::count(),
            'pending' => Institution::where('status', 'pending')->count(),
            'active' => Institution::where('status', 'active')->count(),
            'inactive' => Institution::where('status', 'inactive')->count(),
            'suspended' => Institution::where('status', 'suspended')->count(),
        ];
    }

    public function getStatusBadgeClass($status)
    {
        return match ($status) {
            'active' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getActionButtons($institution)
    {
        $buttons = [];

        switch ($institution->status) {
            case 'pending':
                $buttons[] = [
                    'action' => 'approveInstitution',
                    'params' => $institution->id,
                    'label' => 'Approve',
                    'icon' => 'fas fa-check',
                    'class' => 'text-green-600 hover:text-green-900',
                    'title' => 'Approve Institution',
                ];
                $buttons[] = [
                    'action' => 'rejectInstitution',
                    'params' => $institution->id,
                    'label' => 'Reject',
                    'icon' => 'fas fa-times',
                    'class' => 'text-red-600 hover:text-red-900',
                    'title' => 'Reject Institution',
                ];
                break;
                
            case 'active':
                $buttons[] = [
                    'action' => 'suspendInstitution',
                    'params' => $institution->id,
                    'label' => 'Suspend',
                    'icon' => 'fas fa-pause',
                    'class' => 'text-orange-600 hover:text-orange-900',
                    'title' => 'Suspend Institution',
                ];
                break;
                
            case 'suspended':
                $buttons[] = [
                    'action' => 'activateInstitution',
                    'params' => $institution->id,
                    'label' => 'Activate',
                    'icon' => 'fas fa-play',
                    'class' => 'text-blue-600 hover:text-blue-900',
                    'title' => 'Activate Institution',
                ];
                break;
        }

        return $buttons;
    }
}