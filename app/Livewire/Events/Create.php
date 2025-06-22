<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\Institution;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    // Basic Information
    public $title = '';
    public $description = '';
    public $type = 'workshop';
    public $institution_id = '';
    
    // Date and Time
    public $start_date = '';
    public $start_time = '';
    public $end_date = '';
    public $end_time = '';
    
    // Location
    public $venue = '';
    public $address = '';
    public $latitude = '';
    public $longitude = '';
    
    // Participants
    public $max_participants = '';
    public $allow_non_members = false;
    public $requires_approval = false;
    
    // Registration
    public $is_free = true;
    public $registration_fee = 0;
    public $registration_start_date = '';
    public $registration_start_time = '';
    public $registration_end_date = '';
    public $registration_end_time = '';
    
    // Additional Details
    public $objectives = [];
    public $requirements = [];
    public $target_audience = [];
    public $tags = [];
    public $banner_image;
    
    // Form helpers
    public $newObjective = '';
    public $newRequirement = '';
    public $newTargetAudience = '';
    public $newTag = '';
    
    // Multi-step form
    public $step = 1;
    public $totalSteps = 4;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|min:50',
        'type' => 'required|in:workshop,seminar,training,conference,meeting,competition,other',
        'institution_id' => 'required|exists:institutions,id',
        'start_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required',
        'end_date' => 'required|date|after_or_equal:start_date',
        'end_time' => 'required',
        'venue' => 'required|string|max:255',
        'address' => 'nullable|string|max:255',
        'max_participants' => 'nullable|integer|min:1',
        'registration_fee' => 'nullable|numeric|min:0',
        'registration_start_date' => 'nullable|date|before_or_equal:start_date',
        'registration_end_date' => 'nullable|date|before_or_equal:start_date',
        'banner_image' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'description.min' => 'Event description must be at least 50 characters long.',
        'start_date.after_or_equal' => 'Event start date must be today or in the future.',
        'end_date.after_or_equal' => 'Event end date must be on or after the start date.',
        'registration_start_date.before_or_equal' => 'Registration start must be before the event starts.',
        'registration_end_date.before_or_equal' => 'Registration end must be before the event starts.',
    ];

    public function mount()
    {
        // Auto-fill institution for non-TRA officers
        $user = auth()->user();
        if ($user->role !== 'tra_officer' && $user->members->isNotEmpty()) {
            $this->institution_id = $user->members->first()->institution_id;
        }
        
        // Set default registration dates
        $this->registration_start_date = now()->format('Y-m-d');
        $this->registration_start_time = '08:00';
        $this->registration_end_date = now()->addWeek()->format('Y-m-d');
        $this->registration_end_time = '17:00';
    }

    public function updatedIsFree()
    {
        if ($this->is_free) {
            $this->registration_fee = 0;
        }
    }

    // Objective management
    public function addObjective()
    {
        if (trim($this->newObjective)) {
            $this->objectives[] = trim($this->newObjective);
            $this->newObjective = '';
        }
    }

    public function removeObjective($index)
    {
        unset($this->objectives[$index]);
        $this->objectives = array_values($this->objectives);
    }

    // Requirement management
    public function addRequirement()
    {
        if (trim($this->newRequirement)) {
            $this->requirements[] = trim($this->newRequirement);
            $this->newRequirement = '';
        }
    }

    public function removeRequirement($index)
    {
        unset($this->requirements[$index]);
        $this->requirements = array_values($this->requirements);
    }

    // Target audience management
    public function addTargetAudience()
    {
        if (trim($this->newTargetAudience)) {
            $this->target_audience[] = trim($this->newTargetAudience);
            $this->newTargetAudience = '';
        }
    }

    public function removeTargetAudience($index)
    {
        unset($this->target_audience[$index]);
        $this->target_audience = array_values($this->target_audience);
    }

    // Tag management
    public function addTag()
    {
        if (trim($this->newTag)) {
            $this->tags[] = trim($this->newTag);
            $this->newTag = '';
        }
    }

    public function removeTag($index)
    {
        unset($this->tags[$index]);
        $this->tags = array_values($this->tags);
    }

    // Multi-step navigation
    public function nextStep()
    {
        $this->validateCurrentStep();
        
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

    private function validateCurrentStep()
    {
        $rules = [];
        
        switch ($this->step) {
            case 1:
                $rules = [
                    'title' => 'required|string|max:255',
                    'description' => 'required|string|min:50',
                    'type' => 'required|in:workshop,seminar,training,conference,meeting,competition,other',
                    'institution_id' => 'required|exists:institutions,id',
                ];
                break;
            case 2:
                $rules = [
                    'start_date' => 'required|date|after_or_equal:today',
                    'start_time' => 'required',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'end_time' => 'required',
                    'venue' => 'required|string|max:255',
                ];
                break;
            case 3:
                $rules = [
                    'registration_fee' => 'nullable|numeric|min:0',
                    'registration_start_date' => 'nullable|date|before_or_equal:start_date',
                    'registration_end_date' => 'nullable|date|before_or_equal:start_date',
                ];
                break;
        }
        
        $this->validate($rules);
    }

    public function submit()
    {
        $this->validate();

        // Handle banner image upload
        $bannerPath = null;
        if ($this->banner_image) {
            $bannerPath = $this->banner_image->store('events/banners', 'public');
        }

        // Create the event
        $event = Event::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . time(),
            'description' => $this->description,
            'type' => $this->type,
            'institution_id' => $this->institution_id,
            'created_by' => auth()->id(),
            'start_date' => $this->start_date . ' ' . $this->start_time,
            'end_date' => $this->end_date . ' ' . $this->end_time,
            'venue' => $this->venue,
            'address' => $this->address,
            'latitude' => $this->latitude ?: null,
            'longitude' => $this->longitude ?: null,
            'max_participants' => $this->max_participants ?: null,
            'registration_fee' => $this->is_free ? 0 : $this->registration_fee,
            'is_free' => $this->is_free,
            'requires_approval' => $this->requires_approval,
            'allow_non_members' => $this->allow_non_members,
            'registration_start' => $this->registration_start_date ? 
                $this->registration_start_date . ' ' . $this->registration_start_time : null,
            'registration_end' => $this->registration_end_date ? 
                $this->registration_end_date . ' ' . $this->registration_end_time : null,
            'status' => 'draft',
            'objectives' => json_encode($this->objectives),
            'requirements' => json_encode($this->requirements),
            'target_audience' => json_encode($this->target_audience),
            'tags' => json_encode($this->tags),
            'banner_image' => $bannerPath,
        ]);

        session()->flash('success', 'Event created successfully! You can now publish it when ready.');
        
        return redirect()->route('events.show', $event);
    }

    public function render()
    {
        $institutions = Institution::where('status', 'active')->get();
        
        return view('livewire.events.create', [
            'institutions' => $institutions
        ]);
    }
}