<?php

namespace App\Livewire\Certificate;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class CertificateGallery extends Component
{
    use WithPagination;

    public $viewMode = 'grid'; // grid, list, carousel
    public $filterType = '';
    public $filterStatus = 'active';
    public $sortBy = 'issue_date';
    public $sortDirection = 'desc';
    public $selectedCertificate = null;

    protected $queryString = [
        'filterType' => ['except' => ''],
        'filterStatus' => ['except' => 'active'],
        'sortBy' => ['except' => 'issue_date'],
        'sortDirection' => ['except' => 'desc']
    ];

    protected $listeners = ['certificateUpdated' => 'render'];

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingViewMode()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filterType = '';
        $this->filterStatus = 'active';
        $this->resetPage();
    }

    public function viewCertificate($certificateId)
    {
        try {
            $this->selectedCertificate = Certificate::with([
                'user', 'institution', 'issuer', 'event'
            ])->findOrFail($certificateId);
            
            $this->dispatch('open-certificate-modal');
        } catch (\Exception $e) {
            session()->flash('error', 'Certificate not found.');
        }
    }

    public function closeCertificateModal()
    {
        $this->selectedCertificate = null;
        $this->dispatch('close-certificate-modal');
    }

    public function downloadCertificate($certificateId)
    {
        try {
            $certificate = Certificate::findOrFail($certificateId);
            
            // Check permissions
            if (!$this->canViewCertificate($certificate)) {
                session()->flash('error', 'You do not have permission to download this certificate.');
                return;
            }

            if ($certificate->file_path && file_exists(storage_path('app/' . $certificate->file_path))) {
                return response()->download(storage_path('app/' . $certificate->file_path));
            } else {
                session()->flash('error', 'Certificate file not found.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error downloading certificate.');
        }
    }

    private function canViewCertificate($certificate)
    {
        $user = Auth::user();
        
        // Students can only view their own certificates
        if ($user->role === 'student') {
            return $certificate->user_id === $user->id;
        }
        
        // Leaders and supervisors can view certificates from their institution
        if (in_array($user->role, ['leader', 'supervisor'])) {
            $currentInstitution = $user->currentInstitution ?? $user->institution;
            return $certificate->institution_id === $currentInstitution?->id;
        }
        
        // TRA officers and admins can view all certificates
        return in_array($user->role, ['tra_officer', 'admin']);
    }

    public function getCertificateColorClass($type)
    {
        return match($type) {
            'completion' => 'bg-gradient-to-br from-blue-400 to-blue-500',
            'participation' => 'bg-gradient-to-br from-green-400 to-green-500',
            'achievement' => 'bg-gradient-to-br from-purple-400 to-purple-500',
            'recognition' => 'bg-gradient-to-br from-indigo-400 to-indigo-500',
            default => 'bg-gradient-to-br from-yellow-400 to-yellow-500'
        };
    }

    public function render()
    {
        $query = Certificate::with(['user', 'institution', 'issuer', 'event']);

        // Apply user-based filtering
        $user = Auth::user();
        
        if ($user->role === 'student') {
            $query->where('user_id', $user->id);
        } elseif (in_array($user->role, ['leader', 'supervisor'])) {
            $currentInstitution = $user->currentInstitution ?? $user->institution;
            if ($currentInstitution) {
                $query->where('institution_id', $currentInstitution->id);
            }
        }
        // TRA officers and admins see all certificates (no additional filtering)

        // Apply filters
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterStatus === 'active') {
            $query->where('status', 'active')
                  ->where(function($q) {
                      $q->whereNull('expiry_date')
                        ->orWhere('expiry_date', '>', now());
                  });
        } elseif ($this->filterStatus === 'expired') {
            $query->where('status', 'active')
                  ->where('expiry_date', '<=', now())
                  ->whereNotNull('expiry_date');
        } elseif ($this->filterStatus && $this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $certificates = $query->paginate(12);

        return view('livewire.certificate.certificate-gallery', [
            'certificates' => $certificates
        ]);
    }
}