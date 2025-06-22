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

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
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

    public function viewCertificate($certificateId)
    {
        $this->selectedCertificate = Certificate::with([
            'user', 'institution', 'issuer', 'event'
        ])->findOrFail($certificateId);
        
        $this->dispatchBrowserEvent('open-certificate-modal');
    }

    public function closeCertificateModal()
    {
        $this->selectedCertificate = null;
        $this->dispatchBrowserEvent('close-certificate-modal');
    }

    public function render()
    {
        $query = Certificate::with(['user', 'institution', 'issuer', 'event']);

        // Apply user-based filtering
        if (Auth::user()->role === 'student') {
            $query->where('user_id', Auth::id());
        } elseif (in_array(Auth::user()->role, ['leader', 'supervisor'])) {
            $currentInstitution = Auth::user()->currentInstitution;
            if ($currentInstitution) {
                $query->where('institution_id', $currentInstitution->id);
            }
        }

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
                  ->where('expiry_date', '<', now());
        } elseif ($this->filterStatus) {
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