<?php

namespace App\Livewire\Institutions;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Institution;
use App\Models\Activity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $type = '';

    #[Url(except: '')]
    public $status = '';

    #[Url(except: '')]
    public $region = '';

    #[Url(except: 'name')]
    public $sortBy = 'name';

    #[Url(except: 'asc')]
    public $sortDirection = 'asc';

    public $perPage = 15;

    // Bulk action properties
    public $selectedInstitutions = [];
    public $selectAll = false;
    public $showBulkActions = false;

    // Delete confirmation
    public $confirmingDeletion = false;
    public $institutionToDelete = null;

    public function mount()
    {
      //  $this->authorize('view-institutions');
    }

    public function render()
    {
        $institutions = $this->getInstitutions();
        
        return view('livewire.institutions.index', [
            'institutions' => $institutions,
            'types' => $this->getTypes(),
            'regions' => $this->getRegions(),
            'statuses' => $this->getStatuses(),
        ]);
    }

    private function getInstitutions()
    {
        $query = Institution::with(['creator', 'approver'])
            ->withCount(['members', 'activeMembers', 'events', 'budgets']);

        // Apply search filter
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply type filter
        if ($this->type) {
            $query->where('type', $this->type);
        }

        // Apply status filter
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Apply region filter
        if ($this->region) {
            $query->where('region', $this->region);
        }

        // Apply role-based filtering
        if (!auth()->user()->isTraOfficer()) {
            // Non-TRA users can only see active institutions and their own
            $query->where(function ($q) {
                $q->where('status', 'active')
                  ->orWhere('created_by', auth()->id());
            });
        }

        // Apply sorting
        if ($this->sortBy === 'members_count') {
            $query->orderByMemberCount($this->sortDirection);
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate($this->perPage);
    }

    private function getTypes()
    {
        return [
            'university' => 'University',
            'college' => 'College',
            'institute' => 'Institute',
            'school' => 'School',
        ];
    }

    private function getRegions()
    {
        return Institution::distinct()
            ->whereNotNull('region')
            ->orderBy('region')
            ->pluck('region')
            ->toArray();
    }

    private function getStatuses()
    {
        return [
            'pending' => 'Pending',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended',
        ];
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedRegion()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'type', 'status', 'region']);
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedInstitutions = $this->getInstitutions()->pluck('id')->toArray();
        } else {
            $this->selectedInstitutions = [];
        }
        
        $this->updateBulkActionsVisibility();
    }

    public function updatedSelectedInstitutions()
    {
        $this->updateBulkActionsVisibility();
    }

    private function updateBulkActionsVisibility()
    {
        $this->showBulkActions = count($this->selectedInstitutions) > 0;
    }

    public function approveInstitution($institutionId)
    {
        $this->authorize('manage-institutions');

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

        // Log activity
        Activity::log(
            'institution_approved',
            "Institution '{$institution->name}' was approved",
            auth()->user(),
            $institution
        );

        session()->flash('success', "Institution '{$institution->name}' has been approved successfully.");
    }

    public function rejectInstitution($institutionId)
    {
        $this->authorize('manage-institutions');

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

        // Log activity
        Activity::log(
            'institution_rejected',
            "Institution '{$institution->name}' was rejected",
            auth()->user(),
            $institution
        );

        session()->flash('warning', "Institution '{$institution->name}' has been rejected.");
    }

    public function suspendInstitution($institutionId)
    {
        $this->authorize('manage-institutions');

        $institution = Institution::findOrFail($institutionId);
        
        $institution->update(['status' => 'suspended']);

        // Log activity
        Activity::log(
            'institution_suspended',
            "Institution '{$institution->name}' was suspended",
            auth()->user(),
            $institution
        );

        session()->flash('warning', "Institution '{$institution->name}' has been suspended.");
    }

    public function activateInstitution($institutionId)
    {
        $this->authorize('manage-institutions');

        $institution = Institution::findOrFail($institutionId);
        
        $institution->update(['status' => 'active']);

        // Log activity
        Activity::log(
            'institution_activated',
            "Institution '{$institution->name}' was activated",
            auth()->user(),
            $institution
        );

        session()->flash('success', "Institution '{$institution->name}' has been activated.");
    }

    public function confirmDelete($institutionId)
    {
        $this->authorize('delete-institutions');
        
        $this->institutionToDelete = $institutionId;
        $this->confirmingDeletion = true;
    }

    public function deleteInstitution()
    {
        $this->authorize('delete-institutions');

        if (!$this->institutionToDelete) {
            return;
        }

        $institution = Institution::findOrFail($this->institutionToDelete);
        
        // Check if institution has members or events
        if ($institution->members()->count() > 0 || $institution->events()->count() > 0) {
            session()->flash('error', 'Cannot delete institution with existing members or events.');
            $this->confirmingDeletion = false;
            $this->institutionToDelete = null;
            return;
        }

        $institutionName = $institution->name;
        $institution->delete();

        // Log activity
        Activity::log(
            'institution_deleted',
            "Institution '{$institutionName}' was deleted",
            auth()->user()
        );

        session()->flash('success', "Institution '{$institutionName}' has been deleted.");
        
        $this->confirmingDeletion = false;
        $this->institutionToDelete = null;
    }

    public function bulkApprove()
    {
        $this->authorize('manage-institutions');

        $institutions = Institution::whereIn('id', $this->selectedInstitutions)
            ->where('status', 'pending')
            ->get();

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
        }

        $count = $institutions->count();
        session()->flash('success', "Successfully approved {$count} institutions.");
        
        $this->selectedInstitutions = [];
        $this->selectAll = false;
        $this->updateBulkActionsVisibility();
    }

    public function bulkReject()
    {
        $this->authorize('manage-institutions');

        $institutions = Institution::whereIn('id', $this->selectedInstitutions)
            ->where('status', 'pending')
            ->get();

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
        }

        $count = $institutions->count();
        session()->flash('warning', "Successfully rejected {$count} institutions.");
        
        $this->selectedInstitutions = [];
        $this->selectAll = false;
        $this->updateBulkActionsVisibility();
    }

    public function exportData()
    {
        $this->authorize('export-data');
        
        // This would typically trigger a job to export data
        session()->flash('success', 'Export started. You will receive an email when the file is ready.');
    }

    public function getSortIcon($field)
    {
        if ($this->sortBy !== $field) {
            return 'fas fa-sort text-gray-400';
        }

        return $this->sortDirection === 'asc' 
            ? 'fas fa-sort-up text-yellow-500' 
            : 'fas fa-sort-down text-yellow-500';
    }
}