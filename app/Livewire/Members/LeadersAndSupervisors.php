<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\Institution;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class LeadersAndSupervisors extends Component
{
    use WithPagination;

    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'type')]
    public $typeFilter = '';

    #[Url(as: 'institution')]
    public $institutionFilter = '';

    #[Url(as: 'status')]
    public $statusFilter = '';

    public $perPage = 10;

    public function updatingSearch()
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

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'typeFilter', 'institutionFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Member::with(['user', 'institution', 'approvedBy'])
                      ->whereIn('member_type', ['leader', 'supervisor']);

        // Apply search
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })->orWhere('student_id', 'like', '%' . $this->search . '%')
              ->orWhere('course_of_study', 'like', '%' . $this->search . '%');
        }

        // Apply filters
        if ($this->typeFilter) {
            $query->where('member_type', $this->typeFilter);
        }

        if ($this->institutionFilter) {
            $query->where('institution_id', $this->institutionFilter);
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $leaders = $query->latest()->paginate($this->perPage);
        $institutions = Institution::where('status', 'active')->get();

        return view('livewire.members.leaders-and-supervisors', [
            'leaders' => $leaders,
            'institutions' => $institutions,
        ])->layout('layouts.app');
    }
}