<?php

namespace App\Livewire\Institutions;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Institution;
use App\Models\Activity;
use Illuminate\Support\Str;

class Create extends Component
{
    use WithFileUploads;

    // Basic Information
    public $name = '';
    public $code = '';
    public $type = 'university';
    public $description = '';
    public $established_date = '';

    // Location Information
    public $address = '';
    public $city = '';
    public $region = '';
    public $postal_code = '';

    // Contact Information
    public $phone = '';
    public $email = '';
    public $website = '';

    // Logo
    public $logo;
    public $currentLogo = null;

    // Contact Persons
    public $contactPersons = [
        ['name' => '', 'title' => '', 'email' => '', 'phone' => '']
    ];

    // UI State
    public $currentStep = 1;
    public $totalSteps = 4;

           // $this->authorize('manage-institutions');

    // public function mount()
    // {

    //     // dd("yes");
    // }

    public function render()
    {
        return view('livewire.institutions.create');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:institutions,code',
            'type' => 'required|in:university,college,institute,school',
            'description' => 'nullable|string|max:1000',
            'established_date' => 'nullable|date|before_or_equal:today',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contactPersons.*.name' => 'required_if:contactPersons.*.email,!=,null|string|max:255',
            'contactPersons.*.title' => 'required_if:contactPersons.*.email,!=,null|string|max:255',
            'contactPersons.*.email' => 'nullable|email|max:255',
            'contactPersons.*.phone' => 'nullable|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Institution name is required.',
            'code.required' => 'Institution code is required.',
            'code.unique' => 'This institution code is already taken.',
            'type.required' => 'Please select an institution type.',
            'address.required' => 'Address is required.',
            'city.required' => 'City is required.',
            'region.required' => 'Region is required.',
            'email.email' => 'Please enter a valid email address.',
            'website.url' => 'Please enter a valid website URL.',
            'logo.image' => 'Logo must be an image file.',
            'logo.max' => 'Logo file size cannot exceed 2MB.',
        ];
    }

    public function updatedName()
    {
        if (empty($this->code)) {
            $this->code = $this->generateCode($this->name);
        }
    }

    public function updatedCode()
    {
        $this->code = strtoupper($this->code);
    }

    private function generateCode($name)
    {
        // Generate code from institution name
        $words = explode(' ', $name);
        $code = '';
        
        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $code .= strtoupper(substr($word, 0, 1));
            }
        }
        
        // If code is too short, add more characters
        if (strlen($code) < 3) {
            $code = strtoupper(substr(str_replace(' ', '', $name), 0, 5));
        }
        
        return $code;
    }

    public function nextStep()
    {
        // Validate current step
        $this->validateCurrentStep();
        
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            // Validate all previous steps
            for ($i = 1; $i < $step; $i++) {
                $this->validateStep($i);
            }
            $this->currentStep = $step;
        }
    }

    private function validateCurrentStep()
    {
        $this->validateStep($this->currentStep);
    }

    private function validateStep($step)
    {
        switch ($step) {
            case 1: // Basic Information
                $this->validate([
                    'name' => 'required|string|max:255',
                    'code' => 'required|string|max:20|unique:institutions,code',
                    'type' => 'required|in:university,college,institute,school',
                    'description' => 'nullable|string|max:1000',
                    'established_date' => 'nullable|date|before_or_equal:today',
                ]);
                break;
            
            case 2: // Location Information
                $this->validate([
                    'address' => 'required|string|max:255',
                    'city' => 'required|string|max:100',
                    'region' => 'required|string|max:100',
                    'postal_code' => 'nullable|string|max:20',
                ]);
                break;
                
            case 3: // Contact Information
                $this->validate([
                    'phone' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:255',
                    'website' => 'nullable|url|max:255',
                    'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);
                break;
        }
    }

    public function addContactPerson()
    {
        $this->contactPersons[] = ['name' => '', 'title' => '', 'email' => '', 'phone' => ''];
    }

    public function removeContactPerson($index)
    {
        if (count($this->contactPersons) > 1) {
            unset($this->contactPersons[$index]);
            $this->contactPersons = array_values($this->contactPersons);
        }
    }

    public function save()
    {
        $this->validate();

        try {
            $data = [
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'type' => $this->type,
                'description' => $this->description,
                'established_date' => $this->established_date ?: null,
                'address' => $this->address,
                'city' => $this->city,
                'region' => $this->region,
                'postal_code' => $this->postal_code,
                'phone' => $this->phone,
                'email' => $this->email,
                'website' => $this->website,
                'created_by' => auth()->id(),
            ];

            // Handle logo upload
            if ($this->logo) {
                $logoPath = $this->logo->store('institutions/logos', 'public');
                $data['logo'] = $logoPath;
            }

            // Filter out empty contact persons
            $contactPersons = array_filter($this->contactPersons, function ($person) {
                return !empty($person['email']) || !empty($person['name']);
            });
            
            if (!empty($contactPersons)) {
                $data['contact_persons'] = array_values($contactPersons);
            }

            // Set status based on user role
            if (auth()->user()->isTraOfficer()) {
                $data['status'] = 'active';
                $data['approved_by'] = auth()->id();
                $data['approved_at'] = now();
            } else {
                $data['status'] = 'pending';
            }

            $institution = Institution::create($data);

            // Log activity
            Activity::log(
                $institution->status === 'active' ? 'institution_approved' : 'institution_created',
                "Institution '{$institution->name}' was " . ($institution->status === 'active' ? 'created and approved' : 'created'),
                auth()->user(),
                $institution
            );

            session()->flash('success', 
                $institution->status === 'active' 
                    ? "Institution '{$institution->name}' has been created and approved successfully."
                    : "Institution '{$institution->name}' has been created and is pending approval."
            );

            return redirect()->route('institutions.index');

        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while creating the institution. Please try again.');
            \Log::error('Institution creation error: ' . $e->getMessage());
        }
    }

    public function getStepTitle($step)
    {
        return match ($step) {
            1 => 'Basic Information',
            2 => 'Location Details',
            3 => 'Contact Information',
            4 => 'Review & Submit',
            default => 'Step ' . $step,
        };
    }

    public function getInstitutionTypes()
    {
        return [
            'university' => 'University',
            'college' => 'College',
            'institute' => 'Institute',
            'school' => 'School',
        ];
    }

    public function getTanzanianRegions()
    {
        return [
            'Arusha', 'Dar es Salaam', 'Dodoma', 'Geita', 'Iringa', 'Kagera',
            'Katavi', 'Kigoma', 'Kilimanjaro', 'Lindi', 'Manyara', 'Mara',
            'Mbeya', 'Morogoro', 'Mtwara', 'Mwanza', 'Njombe', 'Pemba North',
            'Pemba South', 'Pwani', 'Rukwa', 'Ruvuma', 'Shinyanga', 'Simiyu',
            'Singida', 'Songwe', 'Tabora', 'Tanga', 'Unguja North', 'Unguja South'
        ];
    }

    public function isStepCompleted($step)
    {
        try {
            $this->validateStep($step);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function isStepAccessible($step)
    {
        if ($step <= $this->currentStep) {
            return true;
        }

        // Check if all previous steps are completed
        for ($i = 1; $i < $step; $i++) {
            if (!$this->isStepCompleted($i)) {
                return false;
            }
        }

        return true;
    }
}