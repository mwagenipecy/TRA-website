<?php

// app/Http/Livewire/Certificate/CertificateIndex.php
namespace App\Livewire\Certificate;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificate;
use App\Models\Institution;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CertificateIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $institutionFilter = '';
    public $eventFilter = '';
    public $yearFilter = '';
    
    protected $queryString = ['search', 'statusFilter', 'typeFilter', 'yearFilter'];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $query = Certificate::with(['user', 'event', 'institution', 'issuer'])
            ->when($this->search, function($q) {
                return $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('certificate_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%');
                        });
            })
            ->when($this->statusFilter, function($q) {
                return $q->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function($q) {
                return $q->where('type', $this->typeFilter);
            })
            ->when($this->institutionFilter, function($q) {
                return $q->where('institution_id', $this->institutionFilter);
            })
            ->when($this->eventFilter, function($q) {
                return $q->where('event_id', $this->eventFilter);
            })
            ->when($this->yearFilter, function($q) {
                return $q->whereYear('issue_date', $this->yearFilter);
            });
            
        // Role-based filtering
        if (Auth::user()->role === 'student') {
            $query->where('user_id', Auth::id());
        } elseif (Auth::user()->role !== 'tra_officer') {
            $currentInstitution = Auth::user()->currentInstitution;
            if ($currentInstitution) {
                $query->where('institution_id', $currentInstitution->id);
            }
        }
        
        $certificates = $query->latest('issue_date')->paginate(10);
        
        $institutions = Institution::where('status', 'active')->get();
        $events = Event::where('status', 'completed')->get();
        $years = Certificate::selectRaw('YEAR(issue_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        return view('livewire.certificate.certificate-index', [
            'certificates' => $certificates,
            'institutions' => $institutions,
            'events' => $events,
            'years' => $years
        ]);
    }
    
    public function downloadCertificate($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        
        // Authorization check
        if (Auth::user()->role === 'student' && $certificate->user_id !== Auth::id()) {
            session()->flash('error', 'Unauthorized access.');
            return;
        }
        
        if ($certificate->file_path && file_exists(storage_path('app/' . $certificate->file_path))) {
            return response()->download(storage_path('app/' . $certificate->file_path));
        }
        
        session()->flash('error', 'Certificate file not found.');
    }
    
    public function revokeCertificate($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        
        // Only TRA officers and certificate issuers can revoke
        if (Auth::user()->role !== 'tra_officer' && $certificate->issued_by !== Auth::id()) {
            session()->flash('error', 'Unauthorized action.');
            return;
        }
        
        $certificate->update([
            'status' => 'revoked',
            'revoked_by' => Auth::id(),
            'revoked_at' => now(),
            'revocation_reason' => 'Revoked by ' . Auth::user()->name
        ]);
        
        session()->flash('message', 'Certificate revoked successfully.');
    }
}