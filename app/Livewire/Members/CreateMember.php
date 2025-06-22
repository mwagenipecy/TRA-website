<?php

namespace App\Livewire\Members;

use App\Models\Member;
use App\Models\User;
use App\Models\Institution;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CreateMember extends Component
{
    public $step = 1;
    public $totalSteps = 3;

    // User Information
    public $name = '';
    public $email = '';
    public $phone = '';
    public $national_id = '';
    public $date_of_birth = '';
    public $gender = '';
    public $password = '';
    public $password_confirmation = '';

    // Member Information
    public $institution_id = '';
    public $student_id = '';
    public $course_of_study = '';
    public $year_of_study = '';
    public $member_type = 'student';
    public $interests = [];
    public $skills = [];
    public $motivation = '';

    // Additional
    public $newInterest = '';
    public $newSkill = '';

    protected function rules()
    {
        $rules = [];

        if ($this->step == 1) {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['nullable', 'string', 'max:255'],
                'national_id' => ['nullable', 'string', 'max:255', 'unique:users'],
                'date_of_birth' => ['nullable', 'date', 'before:today'],
                'gender' => ['nullable', 'in:male,female'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation' => ['required'],
            ];
        } elseif ($this->step == 2) {
            $rules = [
                'institution_id' => ['required', 'exists:institutions,id'],
                'student_id' => ['nullable', 'string', 'max:255'],
                'course_of_study' => ['nullable', 'string', 'max:255'],
                'year_of_study' => ['nullable', 'integer', 'min:1', 'max:10'],
                'member_type' => ['required', 'in:student,leader,supervisor'],
            ];
        } elseif ($this->step == 3) {
            $rules = [
                'motivation' => ['nullable', 'string', 'max:1000'],
                'interests' => ['array'],
                'skills' => ['array'],
            ];
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'institution_id.required' => 'Please select an institution.',
            'password.confirmed' => 'The password confirmation does not match.',
            'email.unique' => 'This email address is already registered.',
            'national_id.unique' => 'This national ID is already registered.',
        ];
    }

    public function nextStep()
    {
        $this->validate();

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
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

    public function submit()
    {
        $this->validate();

        try {
            // Create user
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'national_id' => $this->national_id,
                'date_of_birth' => $this->date_of_birth,
                'gender' => $this->gender,
                'password' => Hash::make($this->password),
                'role' => $this->member_type,
                'status' => 'pending',
            ]);

            // Create member
            $member = Member::create([
                'user_id' => $user->id,
                'institution_id' => $this->institution_id,
                'student_id' => $this->student_id,
                'course_of_study' => $this->course_of_study,
                'year_of_study' => $this->year_of_study,
                'member_type' => $this->member_type,
                'status' => 'pending',
                'interests' => $this->interests,
                'skills' => $this->skills,
                'motivation' => $this->motivation,
            ]);

            session()->flash('success', 'Member has been created successfully and is pending approval.');
            
            return redirect()->route('members.index');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating the member. Please try again.');
        }
    }

    public function render()
    {
        $institutions = Institution::where('status', 'active')->get();

        return view('livewire.members.create-member', [
            'institutions' => $institutions,
        ])->layout('layouts.app');
    }
}