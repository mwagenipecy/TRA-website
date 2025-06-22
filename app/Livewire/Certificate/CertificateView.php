<?php

// app/Http/Livewire/Certificate/CertificateView.php
namespace App\Livewire\Certificate;

use Livewire\Component;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateView extends Component
{
    public $certificate;
    public $certificateId;
    public $viewMode = 'preview'; // preview, print, share
    public $showActions = true;
    public $showQrCode = true;
    public $templateStyle = 'modern'; // modern, formal, classic, minimal
    
    protected $listeners = ['refreshCertificate' => '$refresh'];

    public function mount($id, $viewMode = 'preview', $showActions = true)
    {
        $this->certificateId = $id;
        $this->viewMode = $viewMode;
        $this->showActions = $showActions;
        
        $this->certificate = Certificate::with([
            'user', 
            'event', 
            'institution', 
            'issuer', 
            'revoker'
        ])->findOrFail($id);
        
        // Authorization check
        $this->authorizeAccess();
        
        // Set template style based on certificate data
        $this->templateStyle = $this->certificate->template_used ?? 'modern';
    }

    private function authorizeAccess()
    {
        if (!Auth::check()) {
            // Allow public access for verification only
            if ($this->viewMode !== 'verify') {
                abort(403, 'Authentication required.');
            }
            return;
        }

        $user = Auth::user();
        
        // Students can only view their own certificates
        if ($user->role === 'student' && $this->certificate->user_id !== $user->id) {
            abort(403, 'Unauthorized access to certificate.');
        }
        
        // Institution members can view certificates from their institution
        if (in_array($user->role, ['leader', 'supervisor']) && $user->currentInstitution) {
            if ($this->certificate->institution_id !== $user->currentInstitution->id) {
                abort(403, 'Unauthorized access to certificate.');
            }
        }
        
        // TRA officers can view all certificates
        // No additional check needed for tra_officer role
    }

    public function downloadCertificate()
    {
        if (!$this->showActions || !Auth::check()) {
            return;
        }

        if ($this->certificate->file_path && Storage::disk('private')->exists($this->certificate->file_path)) {
            return Storage::disk('private')->download(
                $this->certificate->file_path,
                $this->certificate->certificate_code . '.pdf'
            );
        }
        
        // Generate PDF if no file exists
        $this->generatePDF();
    }

    public function generatePDF()
    {
        if (!$this->showActions || !Auth::check()) {
            return;
        }

        // This would integrate with a PDF library
        session()->flash('message', 'PDF generation will be implemented with a PDF library.');
        
        // Future implementation:
        // $pdfService = new CertificatePdfService();
        // return $pdfService->generateCertificatePDF($this->certificate);
    }

    public function shareCertificate()
    {
        if (!$this->showActions || !Auth::check()) {
            return;
        }

        $shareUrl = route('certificates.verify') . '?code=' . $this->certificate->certificate_code;
        
        // Copy to clipboard using JavaScript
        $this->dispatchBrowserEvent('copy-to-clipboard', [
            'text' => $shareUrl,
            'message' => 'Certificate verification link copied to clipboard!'
        ]);
    }

    public function printCertificate()
    {
        if (!$this->showActions) {
            return;
        }

        $this->viewMode = 'print';
        $this->dispatchBrowserEvent('print-certificate');
    }

    public function switchTemplate($template)
    {
        if (in_array($template, ['modern', 'formal', 'classic', 'minimal'])) {
            $this->templateStyle = $template;
        }
    }

    public function toggleQrCode()
    {
        $this->showQrCode = !$this->showQrCode;
    }

    public function getVerificationUrl()
    {
        return route('certificates.verify') . '?code=' . $this->certificate->certificate_code;
    }

    public function getCertificateStatus()
    {
        if ($this->certificate->status === 'revoked') {
            return [
                'status' => 'revoked',
                'color' => 'red',
                'icon' => 'ban',
                'message' => 'This certificate has been revoked'
            ];
        }

        if ($this->certificate->isExpired()) {
            return [
                'status' => 'expired',
                'color' => 'gray',
                'icon' => 'clock',
                'message' => 'This certificate has expired'
            ];
        }

        return [
            'status' => 'valid',
            'color' => 'green',
            'icon' => 'check-circle',
            'message' => 'This certificate is valid'
        ];
    }

    public function render()
    {
        return view('livewire.certificate.certificate-view', [
            'status' => $this->getCertificateStatus(),
            'verificationUrl' => $this->getVerificationUrl()
        ]);
    }
}