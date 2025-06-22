<?php

namespace App\Livewire\Certificate;

use Livewire\Component;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class CertificateShow extends Component
{
    public $certificate;
    public $certificateId;
    
    public function mount($id)
    {
        $this->certificateId = $id;
        $this->certificate = Certificate::with(['user', 'event', 'institution', 'issuer', 'revoker'])
            ->findOrFail($id);
            
        // Authorization check
        if (Auth::user()->role === 'student' && $this->certificate->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to certificate.');
        } elseif (Auth::user()->role !== 'tra_officer') {
            $currentInstitution = Auth::user()->currentInstitution;
            if (!$currentInstitution || $this->certificate->institution_id !== $currentInstitution->id) {
                abort(403, 'Unauthorized access to certificate.');
            }
        }
    }
    
    public function downloadCertificate()
    {
        if ($this->certificate->file_path && file_exists(storage_path('app/' . $this->certificate->file_path))) {
            return response()->download(
                storage_path('app/' . $this->certificate->file_path),
                $this->certificate->certificate_code . '.pdf'
            );
        }
        
        session()->flash('error', 'Certificate file not found.');
    }
    
    public function generatePDF()
    {
        // This would integrate with a PDF generation service
        session()->flash('message', 'PDF generation feature coming soon.');
    }
    
    public function render()
    {
        return view('livewire.certificate.certificate-show');
    }
}