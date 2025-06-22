<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\Institution;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Response;

class MemberIndex extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'status')]
    public $statusFilter = '';

    #[Url(as: 'type')]
    public $typeFilter = '';

    #[Url(as: 'institution')]
    public $institutionFilter = '';

    #[Url(as: 'sort')]
    public $sortField = 'created_at';

    #[Url(as: 'direction')]
    public $sortDirection = 'desc';

    public $perPage = 15;
    public $selectAll = false;
    public $selectedMembers = [];
    public $showBulkActions = false;
    public $confirmingDeletion = false;
    public $memberToDelete = null;

    // Updaters to reset pagination
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingInstitutionFilter()
    {
        $this->resetPage();
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedMembers = $this->getFilteredMembers()->pluck('id')->toArray();
        } else {
            $this->selectedMembers = [];
        }
        $this->updateBulkActions();
    }

    public function updatedSelectedMembers()
    {
        $this->updateBulkActions();
    }

    private function updateBulkActions()
    {
        $this->showBulkActions = count($this->selectedMembers) > 0;
        
        // Update selectAll state based on current selection
        $allCurrentIds = $this->getFilteredMembers()->pluck('id')->toArray();
        $this->selectAll = count($this->selectedMembers) === count($allCurrentIds) && count($allCurrentIds) > 0;
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function getSortIcon($field)
    {
        if ($this->sortField !== $field) {
            return 'fas fa-sort text-gray-400';
        }
        
        return $this->sortDirection === 'asc' 
            ? 'fas fa-sort-up text-yellow-600' 
            : 'fas fa-sort-down text-yellow-600';
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'typeFilter', 'institutionFilter']);
        $this->resetPage();
    }

    // Bulk Actions
    public function bulkApprove()
    {
        if (empty($this->selectedMembers)) {
            session()->flash('error', 'Please select members to approve.');
            return;
        }

        $members = Member::whereIn('id', $this->selectedMembers)->get();
        $approved = 0;

        foreach ($members as $member) {
            // Check permission using model method if it exists, otherwise fallback
            if (method_exists($member, 'canBeApprovedBy')) {
                $canApprove = $member->canBeApprovedBy(auth()->user());
            } else {
                $canApprove = $this->canManageMembers(auth()->user());
            }

            if ($canApprove && $member->status === 'pending') {
                $member->approve(auth()->id());
                $approved++;
            }
        }

        $this->selectedMembers = [];
        $this->selectAll = false;
        $this->showBulkActions = false;

        session()->flash('success', "{$approved} members have been approved successfully.");
        $this->dispatch('members-updated');
    }

    public function bulkReject()
    {
        if (empty($this->selectedMembers)) {
            session()->flash('error', 'Please select members to reject.');
            return;
        }

        $members = Member::whereIn('id', $this->selectedMembers)->get();
        $rejected = 0;

        foreach ($members as $member) {
            if ($member->canBeApprovedBy(auth()->user()) && $member->status === 'pending') {
                $member->reject('Bulk rejection');
                $rejected++;
            }
        }

        $this->selectedMembers = [];
        $this->selectAll = false;
        $this->showBulkActions = false;

        session()->flash('success', "{$rejected} members have been rejected.");
        $this->dispatch('members-updated');
    }

    // Individual Actions
    public function approveMember($memberId)
    {
        $member = Member::find($memberId);
        
        if (!$member) {
            session()->flash('error', 'Member not found.');
            return;
        }

        // Check permission using model method if it exists, otherwise fallback
        if (method_exists($member, 'canBeApprovedBy')) {
            $canApprove = $member->canBeApprovedBy(auth()->user());
        } else {
            $canApprove = $this->canManageMembers(auth()->user());
        }

        if (!$canApprove) {
            session()->flash('error', 'You do not have permission to approve this member.');
            return;
        }

        if ($member->status !== 'pending') {
            session()->flash('error', 'Only pending members can be approved.');
            return;
        }

        $member->approve(auth()->id(), 'Quick approval');
        session()->flash('success', 'Member has been approved successfully.');
        $this->dispatch('member-approved');
    }

    public function rejectMember($memberId)
    {
        $member = Member::find($memberId);
        
        if (!$member || !$member->canBeApprovedBy(auth()->user())) {
            session()->flash('error', 'You do not have permission to reject this member.');
            return;
        }

        if ($member->status !== 'pending') {
            session()->flash('error', 'Only pending members can be rejected.');
            return;
        }

        $member->reject('Quick rejection');
        session()->flash('success', 'Member has been rejected.');
        $this->dispatch('member-rejected');
    }

    public function suspendMember($memberId)
    {
        $member = Member::find($memberId);
        
        if (!$member ) {  //|| !$member->canBeEditedBy(auth()->user())
            session()->flash('error', 'You do not have permission to suspend this member.');
            return;
        }

        $member->update(['status' => 'suspended']);
        $member->user->update(['status' => 'suspended']);

        session()->flash('success', 'Member has been suspended.');
        $this->dispatch('member-suspended');
    }

    public function activateMember($memberId)
    {
        $member = Member::find($memberId);
        
        if (!$member ) {  //|| !$member->canBeEditedBy(auth()->user())
            session()->flash('error', 'You do not have permission to activate this member.');
            return;
        }

        $member->update(['status' => 'active']);
        $member->user->update(['status' => 'active']);

        session()->flash('success', 'Member has been activated.');
        $this->dispatch('member-activated');
    }

    public function confirmDelete($memberId)
    {
        $this->memberToDelete = $memberId;
        $this->confirmingDeletion = true;
    }

    public function deleteMember()
    {
        if (!$this->memberToDelete) {
            return;
        }

        $member = Member::find($this->memberToDelete);
        
        if (!$member) {
            session()->flash('error', 'Member not found.');
            $this->confirmingDeletion = false;
            return;
        }

        // Check permissions
        $user = auth()->user();
        $isTraOfficer = $user->role === 'tra_officer';
        
        if (method_exists($member, 'canBeEditedBy')) {
            $canEdit = $member->canBeEditedBy($user);
        } else {
            $canEdit = $this->canManageMembers($user);
        }

        if (!$isTraOfficer && !$canEdit) {
            session()->flash('error', 'You do not have permission to delete this member.');
            $this->confirmingDeletion = false;
            return;
        }

        $memberName = $member->user->name;
        
        // Soft delete the member
        $member->delete();
        
        // Optionally deactivate the user account
        $member->user->update(['status' => 'inactive']);

        $this->confirmingDeletion = false;
        $this->memberToDelete = null;

        session()->flash('success', "Member {$memberName} has been removed successfully.");
        $this->dispatch('member-deleted');
    }

    public function exportData()
    {
        $members = $this->getFilteredMembers();
        
        $csvData = [];
        $csvData[] = [
            'Name', 'Email', 'Institution', 'Student ID', 'Course', 'Year', 
            'Member Type', 'Status', 'Joined Date', 'Phone', 'National ID'
        ];

        foreach ($members as $member) {
            $csvData[] = [
                $member->user->name,
                $member->user->email,
                $member->institution->name,
                $member->student_id,
                $member->course_of_study,
                $member->year_of_study,
                ucfirst($member->member_type),
                ucfirst($member->status),
                $member->joined_date ? $member->joined_date->format('Y-m-d') : '',
                $member->user->phone,
                $member->user->national_id,
            ];
        }

        $filename = 'members_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function getFilteredMembers()
    {
        $query = Member::getManagedMembers(auth()->user())
                      ->with(['user', 'institution', 'approvedBy']);

        // Apply search
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('student_id', 'like', '%' . $this->search . '%')
              ->orWhere('course_of_study', 'like', '%' . $this->search . '%');
        }

        // Apply filters
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter) {
            $query->where('member_type', $this->typeFilter);
        }

        if ($this->institutionFilter) {
            $query->where('institution_id', $this->institutionFilter);
        }

        // Apply sorting
        if ($this->sortField === 'name') {
            $query->join('users', 'members.user_id', '=', 'users.id')
                  ->orderBy('users.name', $this->sortDirection)
                  ->select('members.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->get();
    }

    public function render()
    {
        $query = Member::
        
        //getManagedMembers(auth()->user())
                      with(['user', 'institution', 'approvedBy']);

        // Apply search
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('student_id', 'like', '%' . $this->search . '%')
              ->orWhere('course_of_study', 'like', '%' . $this->search . '%');
        }

        // Apply filters
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->typeFilter) {
            $query->where('member_type', $this->typeFilter);
        }

        if ($this->institutionFilter) {
            $query->where('institution_id', $this->institutionFilter);
        }

        // Apply sorting
        if ($this->sortField === 'name') {
            $query->join('users', 'members.user_id', '=', 'users.id')
                  ->orderBy('users.name', $this->sortDirection)
                  ->select('members.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $members = $query->paginate($this->perPage);
        
        // Get institutions that current user can manage
        $institutions = $this->getManageableInstitutions();

        return view('livewire.members.member-index', [
            'members' => $members,
            'institutions' => $institutions,
        ])->layout('layouts.app');
    }

    private function getManageableInstitutions()
    {
        $user = auth()->user();
        
        // Check if user is TRA officer (either by role or method if it exists)
        $isTraOfficer = $user->role === 'tra_officer' || 
                       (method_exists($user, 'isTraOfficer') && $user->isTraOfficer());
        
        if ($isTraOfficer) {
            return Institution::where('status', 'active')->get();
        }

        // Get institutions where user is a leader/supervisor
        $institutionIds = $user->members()
                             ->whereIn('member_type', ['leader', 'supervisor'])
                             ->where('status', 'active')
                             ->pluck('institution_id');

        return Institution::whereIn('id', $institutionIds)
                         ->where('status', 'active')
                         ->get();
    }

    private function canManageMembers($user)
    {
        // Check if user can manage members
        return $user->role === 'tra_officer' || 
               in_array($user->role, ['leader', 'supervisor']) ||
               $user->members()
                   ->whereIn('member_type', ['leader', 'supervisor'])
                   ->where('status', 'active')
                   ->exists();
    }
}