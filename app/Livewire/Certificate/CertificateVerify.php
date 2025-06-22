<?php

namespace App\Livewire\Certificate;

use Livewire\Component;
use App\Models\Certificate;

class CertificateVerify extends Component
{
    public $verificationCode = '';
    public $verificationHash = '';
    public $certificate = null;
    public $verificationResult = null;
    
    protected $rules = [
        'verificationCode' => 'required|string',
    ];
    
    public function verifyCertificate()
    {
        $this->validate();
        
        $this->certificate = Certificate::with(['user', 'institution', 'event'])
            ->where(function($query) {
                $query->where('certificate_code', $this->verificationCode)
                      ->orWhere('verification_hash', $this->verificationCode);
            })
            ->first();
            
        if ($this->certificate) {
            if ($this->certificate->status === 'active') {
                if ($this->certificate->expiry_date && $this->certificate->expiry_date < now()) {
                    $this->verificationResult = 'expired';
                } else {
                    $this->verificationResult = 'valid';
                }
            } else {
                $this->verificationResult = 'revoked';
            }
        } else {
            $this->verificationResult = 'invalid';
            $this->certificate = null;
        }
    }
    
    // public function reset()
    // {
    //     $this->verificationCode = '';
    //     $this->certificate = null;
    //     $this->verificationResult = null;
    // }
    
    public function render()
    {
        return view('livewire.certificate.certificate-verify');
    }
}