<?php

namespace App\Livewire\Members;

use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PendingMembers extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMember = null;
    public $approvalNotes = '';
    public $showApprovalModal = false;
    public $showRejectionModal = false;
    public $bulkSelected = [];
    public $selectAll = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openApprovalModal($memberId)
    {
        $this->selectedMember = Member::find($memberId);
        $this->approvalNotes = '';
        $this->showApprovalModal = true;
    }

    public function openRejectionModal($memberId)
    {
        $this->selectedMember = Member::find($memberId);
        $this->approvalNotes = '';
        $this->showRejectionModal = true;
    }

    public function approveMember()
    {
        if (false== true) { //$this->selectedMember->canBeApprovedBy(auth()->user())
            session()->flash('error', 'You do not have permission to approve this member.');
            return;
        }

        $this->selectedMember->approve(auth()->id(), $this->approvalNotes);

        session()->flash('success', 'Member has been approved successfully.');
        $this->closeModals();
        $this->dispatch('member-approved');
    }

    public function rejectMember()
    {
        if (!$this->selectedMember->canBeApprovedBy(auth()->user())) {
            session()->flash('error', 'You do not have permission to reject this member.');
            return;
        }

        $this->selectedMember->reject($this->approvalNotes);

        session()->flash('success', 'Member has been rejected.');
        $this->closeModals();
        $this->dispatch('member-rejected');
    }

    public function bulkApprove()
    {
        if (empty($this->bulkSelected)) {
            session()->flash('error', 'Please select members to approve.');
            return;
        }

        $members = Member::whereIn('id', $this->bulkSelected)->get();
        $approved = 0;

        foreach ($members as $member) {
            if ($member->canBeApprovedBy(auth()->user())) {
                $member->approve(auth()->id());
                $approved++;
            }
        }

        session()->flash('success', "{$approved} members have been approved successfully.");
        $this->bulkSelected = [];
        $this->selectAll = false;
        $this->dispatch('members-bulk-approved');
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->bulkSelected = $this->getPendingMembers()->pluck('id')->toArray();
        } else {
            $this->bulkSelected = [];
        }
    }

    public function closeModals()
    {
        $this->showApprovalModal = false;
        $this->showRejectionModal = false;
        $this->selectedMember = null;
        $this->approvalNotes = '';
    }

    private function getPendingMembers()
    {
        $query = Member::with(['user', 'institution'])
                      ->where('status', 'pending');

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('student_id', 'like', '%' . $this->search . '%')
              ->orWhere('course_of_study', 'like', '%' . $this->search . '%');
        }

        return $query->latest();
    }

    public function render()
    {
        $pendingMembers = $this->getPendingMembers()->paginate(10);

        return view('livewire.members.pending-members', [
            'pendingMembers' => $pendingMembers,
        ])->layout('layouts.app');
    }
}