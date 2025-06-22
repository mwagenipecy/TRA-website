<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    use WithFileUploads;

    public Event $event;
    public $userRegistration = null;
    public $canRegister = false;
    public $registrationStatus = null;
    
    // Registration form fields
    public $showRegistrationModal = false;
    public $additionalInfo = '';
    public $agreeToTerms = false;
    public $emergencyContact = '';
    public $dietaryRequirements = '';
    public $specialNeeds = '';
    
    // Payment fields
    public $showPaymentModal = false;
    public $paymentScreenshot = null;
    public $paymentReference = '';
    public $paymentMethod = '';
    public $paymentNotes = '';
    
    // Share modal
    public $showShareModal = false;
    
    protected $rules = [
        'agreeToTerms' => 'accepted',
        'emergencyContact' => 'nullable|string|max:255',
        'dietaryRequirements' => 'nullable|string|max:500',
        'specialNeeds' => 'nullable|string|max:500',
        'additionalInfo' => 'nullable|string|max:1000',
        'paymentScreenshot' => 'required|image|max:2048',
        'paymentReference' => 'required|string|max:255',
        'paymentMethod' => 'required|string|in:bank_transfer,mobile_money,credit_card,cash',
        'paymentNotes' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'agreeToTerms.accepted' => 'You must agree to the terms and conditions to register.',
        'paymentScreenshot.required' => 'Payment screenshot is required for paid events.',
        'paymentScreenshot.image' => 'Please upload a valid image file.',
        'paymentScreenshot.max' => 'Payment screenshot must be less than 2MB.',
        'paymentReference.required' => 'Payment reference number is required.',
        'paymentMethod.required' => 'Please select a payment method.',
    ];

    public function mount(Event $event)
    {
        $this->event = $event->load(['institution', 'creator', 'registrations.user']);
        $this->checkUserRegistration();
        $this->checkRegistrationEligibility();
    }

    private function checkUserRegistration()
    {
        if (auth()->check()) {
            $this->userRegistration = EventRegistration::where('event_id', $this->event->id)
                ->where('user_id', auth()->id())
                ->first();
                
            if ($this->userRegistration) {
                $this->registrationStatus = $this->userRegistration->status;
            }
        }
    }

    private function checkRegistrationEligibility()
    {
        $user = auth()->user();
        
        if (!$user) {
            $this->canRegister = false;
            return;
        }

        // Check if event is published and registration is open
        if ($this->event->status !== 'published') {
            $this->canRegister = false;
            return;
        }

        // Check registration period
        if ($this->event->registration_start && now() < $this->event->registration_start) {
            $this->canRegister = false;
            return;
        }

        if ($this->event->registration_end && now() > $this->event->registration_end) {
            $this->canRegister = false;
            return;
        }

        // Check if user already registered
        if ($this->userRegistration) {
            $this->canRegister = false;
            return;
        }

        // Check member restrictions
        $isMember = $user->members->where('institution_id', $this->event->institution_id)->isNotEmpty();
        
        if (!$this->event->allow_non_members && !$isMember) {
            $this->canRegister = false;
            return;
        }

        // Check maximum participants
        if ($this->event->max_participants) {
            $approvedCount = $this->event->registrations()
                ->whereIn('status', ['approved', 'attended'])
                ->count();
                
            if ($approvedCount >= $this->event->max_participants) {
                $this->canRegister = false;
                return;
            }
        }

        $this->canRegister = true;
    }

    public function openRegistrationModal()
    {
        if (!$this->canRegister) {
            session()->flash('error', 'You are not eligible to register for this event.');
            return;
        }

        $this->resetRegistrationForm();
        $this->showRegistrationModal = true;
    }

    public function closeRegistrationModal()
    {
        $this->showRegistrationModal = false;
        $this->resetRegistrationForm();
    }

    private function resetRegistrationForm()
    {
        $this->additionalInfo = '';
        $this->agreeToTerms = false;
        $this->emergencyContact = '';
        $this->dietaryRequirements = '';
        $this->specialNeeds = '';
        $this->resetValidation();
    }

    public function register()
    {
        $this->validate([
            'agreeToTerms' => 'accepted',
            'emergencyContact' => 'nullable|string|max:255',
            'dietaryRequirements' => 'nullable|string|max:500',
            'specialNeeds' => 'nullable|string|max:500',
            'additionalInfo' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $isMember = $user->members->where('institution_id', $this->event->institution_id)->isNotEmpty();

        $additionalData = [
            'emergency_contact' => $this->emergencyContact,
            'dietary_requirements' => $this->dietaryRequirements,
            'special_needs' => $this->specialNeeds,
            'additional_info' => $this->additionalInfo,
            'user_agent' => request()->userAgent(),
            'ip_address' => request()->ip(),
        ];

        DB::transaction(function () use ($user, $isMember, $additionalData) {
            $registration = EventRegistration::create([
                'event_id' => $this->event->id,
                'user_id' => $user->id,
                'is_member' => $isMember,
                'status' => $this->event->requires_approval ? 'pending' : 'approved',
                'registered_at' => now(),
                'additional_info' => $additionalData,
                'payment_required' => !$this->event->is_free,
                'payment_status' => $this->event->is_free ? null : 'pending',
            ]);

            // Log activity
            activity()
                ->performedOn($registration)
                ->causedBy($user)
                ->withProperties($additionalData)
                ->log('event_registration');

            $this->userRegistration = $registration;
            $this->registrationStatus = $registration->status;
        });

        $this->closeRegistrationModal();

        if (!$this->event->is_free) {
            $this->openPaymentModal();
        } else {
            session()->flash('success', 'Registration successful! ' . 
                ($this->event->requires_approval ? 'Your registration is pending approval.' : 'You are now registered for this event.'));
        }

        $this->canRegister = false;
    }

    public function openPaymentModal()
    {
        if (!$this->userRegistration || $this->userRegistration->payment_status === 'paid') {
            return;
        }

        $this->resetPaymentForm();
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetPaymentForm();
    }

    private function resetPaymentForm()
    {
        $this->paymentScreenshot = null;
        $this->paymentReference = '';
        $this->paymentMethod = '';
        $this->paymentNotes = '';
        $this->resetValidation();
    }

    public function submitPayment()
    {
        $this->validate([
            'paymentScreenshot' => 'required|image|max:2048',
            'paymentReference' => 'required|string|max:255',
            'paymentMethod' => 'required|string|in:bank_transfer,mobile_money,credit_card,cash',
            'paymentNotes' => 'nullable|string|max:500'
        ]);

        if (!$this->userRegistration) {
            session()->flash('error', 'Registration not found.');
            return;
        }

        try {
            DB::transaction(function () {
                // Store payment screenshot
                $screenshotPath = $this->paymentScreenshot->store('payments/screenshots', 'public');

                // Update registration with payment info
                $this->userRegistration->update([
                    'payment_status' => 'pending',
                    'payment_reference' => $this->paymentReference,
                    'payment_date' => now(),
                    'additional_info' => array_merge(
                        $this->userRegistration->additional_info ?? [],
                        [
                            'payment_method' => $this->paymentMethod,
                            'payment_notes' => $this->paymentNotes,
                            'payment_screenshot' => $screenshotPath,
                        ]
                    )
                ]);

                // Log payment submission
                activity()
                    ->performedOn($this->userRegistration)
                    ->causedBy(auth()->user())
                    ->withProperties([
                        'payment_method' => $this->paymentMethod,
                        'payment_reference' => $this->paymentReference,
                        'screenshot_path' => $screenshotPath
                    ])
                    ->log('payment_submitted');
            });

            $this->closePaymentModal();
            session()->flash('success', 'Payment proof submitted successfully! Your payment is pending verification.');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit payment proof. Please try again.');
        }
    }

    public function cancelRegistration()
    {
        if (!$this->userRegistration) {
            return;
        }

        DB::transaction(function () {
            $this->userRegistration->update([
                'status' => 'cancelled'
            ]);

            // Log cancellation
            activity()
                ->performedOn($this->userRegistration)
                ->causedBy(auth()->user())
                ->log('registration_cancelled');
        });

        $this->userRegistration = null;
        $this->registrationStatus = null;
        $this->canRegister = true;
        $this->checkRegistrationEligibility();

        session()->flash('success', 'Registration cancelled successfully.');
    }

    public function openShareModal()
    {
        $this->showShareModal = true;
    }

    public function closeShareModal()
    {
        $this->showShareModal = false;
    }

    public function getShareUrl()
    {
        return route('events.show', $this->event);
    }

    public function copyShareUrl()
    {
        // This will be handled by JavaScript
        $this->dispatch('copy-to-clipboard', $this->getShareUrl());
    }

    public function downloadEventCalendar()
    {
        // Generate ICS file for calendar import
        $icsContent = $this->generateICSFile();
        
        return response($icsContent, 200, [
            'Content-Type' => 'text/calendar',
            'Content-Disposition' => 'attachment; filename="' . $this->event->slug . '.ics"',
        ]);
    }

    private function generateICSFile()
    {
        $startDate = $this->event->start_date->format('Ymd\THis\Z');
        $endDate = $this->event->end_date->format('Ymd\THis\Z');
        $now = now()->format('Ymd\THis\Z');
        
        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//Event Management System//Event//EN\r\n";
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:" . $this->event->id . "@eventmanagement.com\r\n";
        $ics .= "DTSTAMP:{$now}\r\n";
        $ics .= "DTSTART:{$startDate}\r\n";
        $ics .= "DTEND:{$endDate}\r\n";
        $ics .= "SUMMARY:" . $this->event->title . "\r\n";
        $ics .= "DESCRIPTION:" . strip_tags($this->event->description) . "\r\n";
        $ics .= "LOCATION:" . $this->event->venue . "\r\n";
        $ics .= "URL:" . $this->getShareUrl() . "\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";
        
        return $ics;
    }

    public function render()
    {
        // Get approved registrations count
        $approvedRegistrations = $this->event->registrations()
            ->whereIn('status', ['approved', 'attended'])
            ->count();
            
        // Get recent registrations for display
        $recentRegistrations = $this->event->registrations()
            ->with('user')
            ->whereIn('status', ['approved', 'attended'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.events.show', [
            'approvedRegistrations' => $approvedRegistrations,
            'recentRegistrations' => $recentRegistrations,
        ]);
    }
}