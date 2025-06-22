<?php

namespace App\Livewire\Institutions;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Institution;
use App\Models\Member;
use App\Models\Event;
use App\Models\Budget;
use App\Models\Activity;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;

class Show extends Component
{
    use WithPagination, AuthorizesRequests;

    public Institution $institution;
    public $activeTab = 'overview';
    
    // Statistics
    public $stats = [];

    public function mount(Institution $institution)
    {
        $this->institution = $institution->load(['creator', 'approver']);
        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.institutions.show', [
            'members' => $this->members,
            'recentEvents' => $this->recentEvents,
            'recentBudgets' => $this->recentBudgets,
            'recentActivities' => $this->recentActivities,
        ]);
    }

    public function updatedActiveTab()
    {
        $this->resetPage();
    }

    // Computed properties that return data based on active tab
    #[Computed]
    public function members()
    {
        if ($this->activeTab !== 'members') {
            return collect();
        }
        
        return $this->institution->members()
            ->with(['user', 'approver'])
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function recentEvents()
    {
        if ($this->activeTab !== 'events') {
            return collect();
        }
        
        return $this->institution->events()
            ->with(['creator'])
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function recentBudgets()
    {
        if ($this->activeTab !== 'budgets') {
            return collect();
        }
        
        return $this->institution->budgets()
            ->with(['creator', 'approver'])
            ->latest()
            ->paginate(10);
    }

    #[Computed]
    public function recentActivities()
    {
        if ($this->activeTab !== 'activities') {
            return collect();
        }
        
        return Activity::where('institution_id', $this->institution->id)
            ->with(['user'])
            ->latest('performed_at')
            ->paginate(15);
    }

    private function loadStats()
    {
        $this->stats = [
            'total_members' => $this->institution->members()->count(),
            'active_members' => $this->institution->activeMembers()->count(),
            'pending_members' => $this->institution->pendingMembers()->count(),
            'total_events' => $this->institution->events()->count(),
            'upcoming_events' => $this->institution->upcomingEvents()->count(),
            'completed_events' => $this->institution->events()->where('status', 'completed')->count(),
            'total_budgets' => $this->institution->budgets()->count(),
            'approved_budgets' => $this->institution->approvedBudgets()->count(),
            'pending_budgets' => $this->institution->pendingBudgets()->count(),
            'total_budget_amount' => $this->institution->budgets()
                ->where('status', 'approved')
                ->where('financial_year', date('Y'))
                ->sum('approved_amount'),
        ];
    }

    public function approveMember($memberId)
    {
        $this->authorize('manage-members');

        $member = Member::findOrFail($memberId);
        
        if ($member->institution_id !== $this->institution->id) {
            session()->flash('error', 'Member does not belong to this institution.');
            return;
        }

        $member->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Activity::log(
            'member_approved',
            "Member '{$member->user->name}' was approved",
            auth()->user(),
            $member,
            $this->institution
        );

        session()->flash('success', 'Member approved successfully.');
        $this->loadStats();
        // Clear computed property cache
        unset($this->members);
    }

    public function rejectMember($memberId)
    {
        $this->authorize('manage-members');

        $member = Member::findOrFail($memberId);
        
        if ($member->institution_id !== $this->institution->id) {
            session()->flash('error', 'Member does not belong to this institution.');
            return;
        }

        $member->update([
            'status' => 'inactive',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Activity::log(
            'member_rejected',
            "Member '{$member->user->name}' was rejected",
            auth()->user(),
            $member,
            $this->institution
        );

        session()->flash('warning', 'Member rejected.');
        $this->loadStats();
        // Clear computed property cache
        unset($this->members);
    }

    public function approveInstitution()
    {
        $this->authorize('manage-institutions');

        if ($this->institution->status !== 'pending') {
            session()->flash('error', 'Only pending institutions can be approved.');
            return;
        }

        $this->institution->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Activity::log(
            'institution_approved',
            "Institution '{$this->institution->name}' was approved",
            auth()->user(),
            $this->institution
        );

        session()->flash('success', 'Institution approved successfully.');
        $this->institution->refresh();
    }

    public function rejectInstitution()
    {
        $this->authorize('manage-institutions');

        if ($this->institution->status !== 'pending') {
            session()->flash('error', 'Only pending institutions can be rejected.');
            return;
        }

        $this->institution->update([
            'status' => 'inactive',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Activity::log(
            'institution_rejected',
            "Institution '{$this->institution->name}' was rejected",
            auth()->user(),
            $this->institution
        );

        session()->flash('warning', 'Institution rejected.');
        $this->institution->refresh();
    }

    public function suspendInstitution()
    {
        $this->authorize('manage-institutions');

        $this->institution->update(['status' => 'suspended']);

        Activity::log(
            'institution_suspended',
            "Institution '{$this->institution->name}' was suspended",
            auth()->user(),
            $this->institution
        );

        session()->flash('warning', 'Institution suspended.');
        $this->institution->refresh();
    }

    public function activateInstitution()
    {
        $this->authorize('manage-institutions');

        $this->institution->update(['status' => 'active']);

        Activity::log(
            'institution_activated',
            "Institution '{$this->institution->name}' was activated",
            auth()->user(),
            $this->institution
        );

        session()->flash('success', 'Institution activated.');
        $this->institution->refresh();
    }

    public function getStatusBadgeClass()
    {
        return match ($this->institution->status) {
            'active' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'suspended' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getActionButtons()
    {
        $buttons = [];

        if (!auth()->user()->hasPermission('manage-institutions')) {
            return $buttons;
        }

        switch ($this->institution->status) {
            case 'pending':
                $buttons[] = [
                    'action' => 'approveInstitution',
                    'label' => 'Approve',
                    'icon' => 'fas fa-check',
                    'class' => 'bg-green-600 hover:bg-green-700 text-white',
                ];
                $buttons[] = [
                    'action' => 'rejectInstitution',
                    'label' => 'Reject',
                    'icon' => 'fas fa-times',
                    'class' => 'bg-red-600 hover:bg-red-700 text-white',
                ];
                break;
                
            case 'active':
                $buttons[] = [
                    'action' => 'suspendInstitution',
                    'label' => 'Suspend',
                    'icon' => 'fas fa-pause',
                    'class' => 'bg-orange-600 hover:bg-orange-700 text-white',
                ];
                break;
                
            case 'suspended':
                $buttons[] = [
                    'action' => 'activateInstitution',
                    'label' => 'Activate',
                    'icon' => 'fas fa-play',
                    'class' => 'bg-blue-600 hover:bg-blue-700 text-white',
                ];
                break;
        }

        return $buttons;
    }

    public function getMemberStatusBadgeClass($status)
    {
        return match ($status) {
            'active' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'inactive' => 'bg-gray-100 text-gray-800',
            'graduated' => 'bg-blue-100 text-blue-800',
            'suspended' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getEventStatusBadgeClass($status)
    {
        return match ($status) {
            'published' => 'bg-green-100 text-green-800',
            'draft' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'postponed' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getBudgetStatusBadgeClass($status)
    {
        return match ($status) {
            'approved' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'under_review' => 'bg-purple-100 text-purple-800',
            'rejected' => 'bg-red-100 text-red-800',
            'draft' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}