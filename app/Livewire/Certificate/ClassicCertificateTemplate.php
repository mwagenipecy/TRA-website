<?php

// app/Http/Livewire/Certificate/ClassicCertificateTemplate.php
namespace App\Livewire\Certificate;

use Livewire\Component;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ClassicCertificateTemplate extends Component
{
    public $certificate;
    public $certificateId;
    
    // Template customization properties
    public $borderStyle = 'double'; // single, double, decorative
    public $sealStyle = 'ceremonial'; // simple, ceremonial, official
    public $colorScheme = 'amber'; // amber, gold, bronze, emerald
    public $fontSize = 'normal'; // small, normal, large
    public $showPattern = true;
    public $showCorners = true;
    public $showSeal = true;
    public $showVerificationCode = true;
    
    // Display options
    public $viewMode = 'display'; // display, preview, print, customize
    public $showCustomization = false;
    public $isPreviewMode = false;
    
    // Animation and effects
    public $enableAnimations = true;
    public $showLoadingEffect = false;
    
    // Template data
    public $templateData = [];
    public $institutionBranding = [];
    public $certificateMetrics = [];

    protected $listeners = [
        'refreshTemplate' => '$refresh',
        'updateTemplateStyle' => 'updateStyle',
        'exportTemplate' => 'exportAsPdf'
    ];

    public function mount($certificateId=null, $viewMode = 'display', $customizations = [])
    {
        $this->certificateId =1; // $certificateId;
        $this->viewMode = $viewMode;
        
        $this->loadCertificate();
        $this->applyCustomizations($customizations);
        $this->loadTemplateData();
        $this->loadInstitutionBranding();
        $this->calculateMetrics();
        
        $this->isPreviewMode = in_array($this->viewMode, ['preview', 'customize']);
    }

    private function loadCertificate()
    {
        $this->certificate = Certificate::with([
            'user',
            'institution',
            'issuer',
            'event',
            'revoker'
        ])->findOrFail($this->certificateId);
        
        // Authorization check
      //  $this->authorizeAccess();
    }

    private function authorizeAccess()
    {
        if (!Auth::check() && $this->viewMode !== 'preview') {
            abort(403, 'Authentication required.');
        }

        if (Auth::check()) {
            $user = Auth::user();
            
            // Students can only view their own certificates
            if ($user->role === 'student' && $this->certificate->user_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }
            
            // Institution staff can view certificates from their institution
            if (in_array($user->role, ['leader', 'supervisor'])) {
                $currentInstitution = $user->currentInstitution;
                if (!$currentInstitution || $this->certificate->institution_id !== $currentInstitution->id) {
                    abort(403, 'Unauthorized access.');
                }
            }
        }
    }

    private function applyCustomizations($customizations)
    {
        if (!empty($customizations)) {
            $this->borderStyle = $customizations['borderStyle'] ?? $this->borderStyle;
            $this->sealStyle = $customizations['sealStyle'] ?? $this->sealStyle;
            $this->colorScheme = $customizations['colorScheme'] ?? $this->colorScheme;
            $this->fontSize = $customizations['fontSize'] ?? $this->fontSize;
            $this->showPattern = $customizations['showPattern'] ?? $this->showPattern;
            $this->showCorners = $customizations['showCorners'] ?? $this->showCorners;
            $this->showSeal = $customizations['showSeal'] ?? $this->showSeal;
            $this->showVerificationCode = $customizations['showVerificationCode'] ?? $this->showVerificationCode;
        }
    }

    private function loadTemplateData()
    {
        $data = $this->certificate->certificate_data ?? [];
        
        $this->templateData = [
            'course_name' => $data['course_name'] ?? 'Professional Development Program',
            'duration' => $data['duration'] ?? 'Variable Duration',
            'grade' => $data['grade'] ?? 'Satisfactory',
            'instructor' => $data['instructor'] ?? $this->certificate->issuer->name,
            'achievement_details' => $data['achievement_details'] ?? 'Successfully completed all required components of the program.',
            'specialization' => $data['specialization'] ?? null,
            'credits' => $data['credits'] ?? null,
            'level' => $data['level'] ?? null
        ];
    }

    private function loadInstitutionBranding()
    {
        $institution = $this->certificate->institution;
        
        $this->institutionBranding = [
            'name' => $institution->name,
            'code' => $institution->code,
            'logo_url' => $institution->logo ?? null,
            'motto' => $institution->motto ?? null,
            'established' => $institution->established_date ? 
                            Carbon::parse($institution->established_date)->format('Y') : null,
            'colors' => [
                'primary' => $this->getInstitutionPrimaryColor(),
                'secondary' => $this->getInstitutionSecondaryColor()
            ]
        ];
    }

    private function calculateMetrics()
    {
        $this->certificateMetrics = [
            'issue_age_days' => $this->certificate->issue_date->diffInDays(now()),
            'is_expired' => $this->certificate->isExpired(),
            'days_until_expiry' => $this->certificate->expiry_date ? 
                                  now()->diffInDays($this->certificate->expiry_date, false) : null,
            'is_recently_issued' => $this->certificate->issue_date->diffInDays(now()) <= 30,
            'academic_year' => $this->getAcademicYear(),
            'semester' => $this->getSemester()
        ];
    }

    public function toggleCustomization()
    {
        $this->showCustomization = !$this->showCustomization;
        $this->viewMode = $this->showCustomization ? 'customize' : 'display';
    }

    public function updateStyle($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
            $this->dispatchBrowserEvent('template-updated', [
                'property' => $property,
                'value' => $value
            ]);
        }
    }

    public function updateBorderStyle($style)
    {
        $this->borderStyle = $style;
        $this->emitStyleUpdate();
    }

    public function updateSealStyle($style)
    {
        $this->sealStyle = $style;
        $this->emitStyleUpdate();
    }

    public function updateColorScheme($scheme)
    {
        $this->colorScheme = $scheme;
        $this->emitStyleUpdate();
    }

    public function updateFontSize($size)
    {
        $this->fontSize = $size;
        $this->emitStyleUpdate();
    }

    public function togglePattern()
    {
        $this->showPattern = !$this->showPattern;
        $this->emitStyleUpdate();
    }

    public function toggleCorners()
    {
        $this->showCorners = !$this->showCorners;
        $this->emitStyleUpdate();
    }

    public function toggleSeal()
    {
        $this->showSeal = !$this->showSeal;
        $this->emitStyleUpdate();
    }

    public function toggleVerificationCode()
    {
        $this->showVerificationCode = !$this->showVerificationCode;
        $this->emitStyleUpdate();
    }

    private function emitStyleUpdate()
    {
        $this->dispatchBrowserEvent('classic-template-updated', [
            'borderStyle' => $this->borderStyle,
            'sealStyle' => $this->sealStyle,
            'colorScheme' => $this->colorScheme,
            'fontSize' => $this->fontSize,
            'showPattern' => $this->showPattern,
            'showCorners' => $this->showCorners,
            'showSeal' => $this->showSeal,
            'showVerificationCode' => $this->showVerificationCode
        ]);
    }

    public function exportAsPdf()
    {
        $this->showLoadingEffect = true;
        
        try {
            // Here you would integrate with a PDF generation library
            // For demonstration, we'll simulate the process
            
            $customizations = [
                'borderStyle' => $this->borderStyle,
                'sealStyle' => $this->sealStyle,
                'colorScheme' => $this->colorScheme,
                'fontSize' => $this->fontSize,
                'showPattern' => $this->showPattern,
                'showCorners' => $this->showCorners,
                'showSeal' => $this->showSeal,
                'showVerificationCode' => $this->showVerificationCode
            ];
            
            // Simulate PDF generation delay
            sleep(2);
            
            $this->showLoadingEffect = false;
            
            $this->dispatchBrowserEvent('pdf-ready', [
                'filename' => $this->certificate->certificate_code . '_classic.pdf',
                'message' => 'Classic certificate PDF generated successfully!'
            ]);
            
        } catch (\Exception $e) {
            $this->showLoadingEffect = false;
            $this->dispatchBrowserEvent('pdf-error', [
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ]);
        }
    }

    public function resetToDefaults()
    {
        $this->borderStyle = 'double';
        $this->sealStyle = 'ceremonial';
        $this->colorScheme = 'amber';
        $this->fontSize = 'normal';
        $this->showPattern = true;
        $this->showCorners = true;
        $this->showSeal = true;
        $this->showVerificationCode = true;
        
        $this->emitStyleUpdate();
        
        session()->flash('message', 'Template settings reset to defaults.');
    }

    public function saveCustomization()
    {
        if (!Auth::check()) {
            return;
        }

        $customizations = [
            'borderStyle' => $this->borderStyle,
            'sealStyle' => $this->sealStyle,
            'colorScheme' => $this->colorScheme,
            'fontSize' => $this->fontSize,
            'showPattern' => $this->showPattern,
            'showCorners' => $this->showCorners,
            'showSeal' => $this->showSeal,
            'showVerificationCode' => $this->showVerificationCode
        ];

        // Save to user preferences or certificate metadata
        $this->certificate->update([
            'template_customizations' => json_encode($customizations)
        ]);

        session()->flash('message', 'Template customization saved successfully!');
    }

    public function printCertificate()
    {
        $this->viewMode = 'print';
        $this->dispatchBrowserEvent('print-classic-certificate', [
            'certificateId' => $this->certificateId
        ]);
    }

    public function shareCertificate()
    {
        $shareUrl = route('certificates.verify') . '?code=' . $this->certificate->certificate_code;
        
        $this->dispatchBrowserEvent('share-certificate', [
            'url' => $shareUrl,
            'title' => 'Classic Certificate - ' . $this->certificate->title,
            'text' => 'Verify this authentic certificate issued to ' . $this->certificate->user->name
        ]);
    }

    // Helper methods for template data
    private function getInstitutionPrimaryColor()
    {
        // You could store this in the institution model
        // For now, return default based on color scheme
        $colors = [
            'amber' => '#f59e0b',
            'gold' => '#eab308',
            'bronze' => '#a16207',
            'emerald' => '#10b981'
        ];
        
        return $colors[$this->colorScheme] ?? $colors['amber'];
    }

    private function getInstitutionSecondaryColor()
    {
        $colors = [
            'amber' => '#d97706',
            'gold' => '#ca8a04',
            'bronze' => '#92400e',
            'emerald' => '#059669'
        ];
        
        return $colors[$this->colorScheme] ?? $colors['amber'];
    }

    private function getAcademicYear()
    {
        $issueDate = $this->certificate->issue_date;
        $year = $issueDate->year;
        
        // Academic year typically starts in September
        if ($issueDate->month >= 9) {
            return $year . '-' . ($year + 1);
        } else {
            return ($year - 1) . '-' . $year;
        }
    }

    private function getSemester()
    {
        $month = $this->certificate->issue_date->month;
        
        if ($month >= 9 || $month <= 1) {
            return 'Fall';
        } elseif ($month >= 2 && $month <= 5) {
            return 'Spring';
        } else {
            return 'Summer';
        }
    }

    public function getBorderClasses()
    {
        $baseClasses = '';
        
        switch ($this->borderStyle) {
            case 'single':
                $baseClasses = 'border-2 border-' . $this->colorScheme . '-600';
                break;
            case 'double':
                $baseClasses = 'border-4 border-double border-' . $this->colorScheme . '-600';
                break;
            case 'decorative':
                $baseClasses = 'border-4 border-dashed border-' . $this->colorScheme . '-600';
                break;
        }
        
        return $baseClasses;
    }

    public function getColorClasses()
    {
        return [
            'primary' => $this->colorScheme . '-600',
            'secondary' => $this->colorScheme . '-700',
            'light' => $this->colorScheme . '-100',
            'background' => $this->colorScheme . '-50'
        ];
    }

    public function getFontSizeClasses()
    {
        $sizes = [
            'small' => [
                'title' => 'text-4xl',
                'name' => 'text-3xl',
                'content' => 'text-lg',
                'details' => 'text-sm'
            ],
            'normal' => [
                'title' => 'text-6xl',
                'name' => 'text-5xl',
                'content' => 'text-2xl',
                'details' => 'text-lg'
            ],
            'large' => [
                'title' => 'text-8xl',
                'name' => 'text-7xl',
                'content' => 'text-3xl',
                'details' => 'text-xl'
            ]
        ];
        
        return $sizes[$this->fontSize] ?? $sizes['normal'];
    }

    public function getCustomizationOptions()
    {
        return [
            'borderStyles' => [
                'single' => 'Single Border',
                'double' => 'Double Border',
                'decorative' => 'Decorative Border'
            ],
            'sealStyles' => [
                'simple' => 'Simple Seal',
                'ceremonial' => 'Ceremonial Seal',
                'official' => 'Official Seal'
            ],
            'colorSchemes' => [
                'amber' => 'Classic Amber',
                'gold' => 'Elegant Gold',
                'bronze' => 'Vintage Bronze',
                'emerald' => 'Distinguished Emerald'
            ],
            'fontSizes' => [
                'small' => 'Compact',
                'normal' => 'Standard',
                'large' => 'Large Print'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.certificate.classic-certificate-template', [
            'borderClasses' => $this->getBorderClasses(),
            'colorClasses' => $this->getColorClasses(),
            'fontSizeClasses' => $this->getFontSizeClasses(),
            'customizationOptions' => $this->getCustomizationOptions()
        ]);
    }
}