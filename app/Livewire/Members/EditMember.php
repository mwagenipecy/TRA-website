<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\Institution;
use Livewire\Component;
use Illuminate\Validation\Rule;

class EditMember extends Component
{
    public Member $member;
    
    // Member Information
    public $institution_id;
    public $student_id;
    public $course_of_study;
    public $year_of_study;
    public $member_type;
    public $status;
    public $interests = [];
    public $skills = [];
    public $motivation;
    public $joined_date;
    public $graduation_date;

    // Additional
    public $newInterest = '';
    public $newSkill = '';
    public $approval_notes = '';

    public function mount(Member $member)
    {
        $this->member = $member;
        
        // Populate form fields
        $this->institution_id = $member->institution_id;
        $this->student_id = $member->student_id;
        $this->course_of_study = $member->course_of_study;
        $this->year_of_study = $member->year_of_study;
        $this->member_type = $member->member_type;
        $this->status = $member->status;
        $this->interests = $member->interests ?? [];
        $this->skills = $member->skills ?? [];
        $this->motivation = $member->motivation;
        $this->joined_date = $member->joined_date?->format('Y-m-d');
        $this->graduation_date = $member->graduation_date?->format('Y-m-d');
        $this->approval_notes = $member->approval_notes;
    }

    protected function rules()
    {
        return [
            'institution_id' => ['required', 'exists:institutions,id'],
            'student_id' => ['nullable', 'string', 'max:255'],
            'course_of_study' => ['nullable', 'string', 'max:255'],
            'year_of_study' => ['nullable', 'integer', 'min:1', 'max:10'],
            'member_type' => ['required', 'in:student,leader,supervisor'],
            'status' => ['required', 'in:active,inactive,pending,graduated,suspended'],
            'motivation' => ['nullable', 'string', 'max:1000'],
            'interests' => ['array'],
            'skills' => ['array'],
            'joined_date' => ['nullable', 'date'],
            'graduation_date' => ['nullable', 'date', 'after:joined_date'],
            'approval_notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function addInterest()
    {
        if ($this->newInterest && !in_array($this->newInterest, $this->interests)) {
            $this->interests[] = $this->newInterest;
            $this->newInterest = '';
        }
    }

    public function removeInterest($index)
    {
        unset($this->interests[$index]);
        $this->interests = array_values($this->interests);
    }

    public function addSkill()
    {
        if ($this->newSkill && !in_array($this->newSkill, $this->skills)) {
            $this->skills[] = $this->newSkill;
            $this->newSkill = '';
        }
    }

    public function removeSkill($index)
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function updateMember()
    {
        $this->validate();

        try {
            $this->member->update([
                'institution_id' => $this->institution_id,
                'student_id' => $this->student_id,
                'course_of_study' => $this->course_of_study,
                'year_of_study' => $this->year_of_study,
                'member_type' => $this->member_type,
                'status' => $this->status,
                'interests' => $this->interests,
                'skills' => $this->skills,
                'motivation' => $this->motivation,
                'joined_date' => $this->joined_date,
                'graduation_date' => $this->graduation_date,
                'approval_notes' => $this->approval_notes,
            ]);

            // Update user role if member type changed
            if ($this->member->user->role !== $this->member_type) {
                $this->member->user->update(['role' => $this->member_type]);
            }

            session()->flash('success', 'Member information has been updated successfully.');
            
            return redirect()->route('members.index');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while updating the member. Please try again.');
        }
    }

    public function approveMember()
    {
        if (!$this->member->canBeApprovedBy(auth()->user())) {
            session()->flash('error', 'You do not have permission to approve this member.');
            return;
        }

        $this->member->approve(auth()->id(), $this->approval_notes);
        $this->status = 'active';

        session()->flash('success', 'Member has been approved successfully.');
        $this->dispatch('member-approved');
    }

    public function rejectMember()
    {
        if (!$this->member->canBeApprovedBy(auth()->user())) {
            session()->flash('error', 'You do not have permission to reject this member.');
            return;
        }

        $this->member->reject($this->approval_notes);
        $this->status = 'inactive';

        session()->flash('success', 'Member has been rejected.');
        $this->dispatch('member-rejected');
    }

    public function render()
    {
        $institutions = Institution::where('status', 'active')->get();

        return view('livewire.members.edit-member', [
            'institutions' => $institutions,
        ])->layout('layouts.app');
    }
}