<?php

namespace App\Livewire\Certificate;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Event;
use App\Models\Institution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CertificateCreate extends Component
{
    use WithFileUploads;
    
    public $title;
    public $description;
    public $type = 'completion';
    public $user_id;
    public $event_id;
    public $issue_date;
    public $expiry_date;
    public $special_notes;
    public $template_used = 'default';
    public $certificate_file;
    
    public $selectedUsers = [];
    public $bulkIssue = false;
    
    public $certificate_data = [
        'course_name' => '',
        'duration' => '',
        'grade' => '',
        'instructor' => '',
        'achievement_details' => ''
    ];
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'type' => 'required|in:completion,participation,achievement,recognition',
        'user_id' => 'required_unless:bulkIssue,true|exists:users,id',
        'issue_date' => 'required|date',
        'expiry_date' => 'nullable|date|after:issue_date',
        'certificate_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'selectedUsers' => 'required_if:bulkIssue,true|array|min:1',
        'selectedUsers.*' => 'exists:users,id'
    ];
    
    public function mount()
    {
        $this->issue_date = date('Y-m-d');
    }
    
    public function toggleBulkIssue()
    {
        $this->bulkIssue = !$this->bulkIssue;
        $this->reset(['user_id', 'selectedUsers']);
    }
    
    public function addUser($userId)
    {
        if (!in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers[] = $userId;
        }
    }
    
    public function removeUser($userId)
    {
        $this->selectedUsers = array_filter($this->selectedUsers, function($id) use ($userId) {
            return $id != $userId;
        });
        $this->selectedUsers = array_values($this->selectedUsers);
    }
    
    public function issueCertificate()
    {
        $this->validate();
        
        $currentInstitution = Auth::user()->currentInstitution;
        if (!$currentInstitution) {
            session()->flash('error', 'You must belong to an institution to issue certificates.');
            return;
        }
        
        $users = $this->bulkIssue ? $this->selectedUsers : [$this->user_id];
        $issued = 0;
        
        foreach ($users as $userId) {
            $certificateCode = $this->generateCertificateCode();
            $verificationHash = $this->generateVerificationHash();
            
            // Handle file upload
            $filePath = null;
            if ($this->certificate_file) {
                $filePath = $this->certificate_file->storeAs(
                    'certificates',
                    $certificateCode . '.' . $this->certificate_file->getClientOriginalExtension(),
                    'private'
                );
            }
            
            Certificate::create([
                'certificate_code' => $certificateCode,
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->type,
                'user_id' => $userId,
                'event_id' => $this->event_id,
                'institution_id' => $currentInstitution->id,
                'issued_by' => Auth::id(),
                'issue_date' => $this->issue_date,
                'expiry_date' => $this->expiry_date,
                'verification_hash' => $verificationHash,
                'certificate_data' => json_encode($this->certificate_data),
                'template_used' => $this->template_used,
                'special_notes' => $this->special_notes,
                'file_path' => $filePath,
                'status' => 'active'
            ]);
            
            $issued++;
        }
        
        session()->flash('message', "Successfully issued {$issued} certificate(s).");
        return redirect()->route('certificates.index');
    }
    
    private function generateCertificateCode()
    {
        $currentInstitution = Auth::user()->currentInstitution;
        $prefix = strtoupper(substr($currentInstitution->code, 0, 3));
        $year = date('Y');
        $type = strtoupper(substr($this->type, 0, 3));
        $number = str_pad(Certificate::where('institution_id', $currentInstitution->id)
                          ->whereYear('issue_date', $year)
                          ->count() + 1, 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}-{$type}-{$number}";
    }
    
    private function generateVerificationHash()
    {
        return hash('sha256', Str::random(40) . time() . Auth::id());
    }
    
    public function render()
    {
        $users = collect();
        $events = collect();
        
        if (Auth::user()->role === 'tra_officer') {
            $users = User::where('status', 'active')->get();
            $events = Event::where('status', 'completed')->get();
        } else {
            $currentInstitution = Auth::user()->currentInstitution;
            if ($currentInstitution) {
                $users = User::whereHas('members', function($q) use ($currentInstitution) {
                    $q->where('institution_id', $currentInstitution->id)
                      ->where('status', 'active');
                })->get();
                
                $events = Event::where('institution_id', $currentInstitution->id)
                    ->where('status', 'completed')
                    ->get();
            }
        }
        
        return view('livewire.certificate.certificate-create', [
            'users' => $users,
            'events' => $events
        ]);
    }
}