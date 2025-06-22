<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Registrations extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $eventFilter = '';
    public $paymentStatusFilter = '';
    public $dateRange = '';
    public $selectedRegistrations = [];
    public $selectAll = false;

    // Bulk action properties
    public $bulkAction = '';
    public $bulkApprovalNotes = '';
    public $showBulkModal = false;

    // Individual registration properties
    public $selectedRegistration = null;
    public $showRegistrationModal = false;
    public $registrationNotes = '';
    public $registrationAction = '';

    // Export properties
    public $showExportModal = false;
    public $exportFormat = 'csv';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'eventFilter' => ['except' => ''],
        'paymentStatusFilter' => ['except' => ''],
        'dateRange' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
        $this->selectedRegistrations = [];
        $this->selectAll = false;
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
        $this->selectedRegistrations = [];
        $this->selectAll = false;
    }

    public function updatingEventFilter()
    {
        $this->resetPage();
        $this->selectedRegistrations = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedRegistrations = $this->getFilteredRegistrations()
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedRegistrations = [];
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->eventFilter = '';
        $this->paymentStatusFilter = '';
        $this->dateRange = '';
        $this->selectedRegistrations = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function showRegistrationDetails($registrationId)
    {
        $this->selectedRegistration = EventRegistration::with([
            'event.institution', 
            'user', 
            'approvedBy'
        ])->findOrFail($registrationId);
        
        $this->registrationNotes = '';
        $this->registrationAction = '';
        $this->showRegistrationModal = true;
    }

    public function closeRegistrationModal()
    {
        $this->showRegistrationModal = false;
        $this->selectedRegistration = null;
        $this->registrationNotes = '';
        $this->registrationAction = '';
    }

    public function approveRegistration($registrationId, $notes = '')
    {
        $registration = EventRegistration::findOrFail($registrationId);
        
        // Check permissions
        // if (!$this->canManageRegistration($registration)) {
        //     session()->flash('error', 'You do not have permission to approve this registration.');
        //     return;
        // }

        DB::transaction(function () use ($registration, $notes) {
            $registration->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'approval_notes' => $notes
            ]);

            // Log activity
            activity()
                ->performedOn($registration)
                ->causedBy(auth()->user())
                ->withProperties(['notes' => $notes])
                ->log('registration_approved');
        });

        // Send notification (you can implement this)
        // $registration->user->notify(new RegistrationApproved($registration));

        session()->flash('success', 'Registration approved successfully.');
        
        if ($this->showRegistrationModal) {
            $this->closeRegistrationModal();
        }
    }

    public function rejectRegistration($registrationId, $notes = '')
    {
        $registration = EventRegistration::findOrFail($registrationId);
        
        // Check permissions
        if (!$this->canManageRegistration($registration)) {
            session()->flash('error', 'You do not have permission to reject this registration.');
            return;
        }

        DB::transaction(function () use ($registration, $notes) {
            $registration->update([
                'status' => 'rejected',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'approval_notes' => $notes
            ]);

            // Log activity
            activity()
                ->performedOn($registration)
                ->causedBy(auth()->user())
                ->withProperties(['notes' => $notes])
                ->log('registration_rejected');
        });

        // Send notification (you can implement this)
        // $registration->user->notify(new RegistrationRejected($registration));

        session()->flash('success', 'Registration rejected.');
        
        if ($this->showRegistrationModal) {
            $this->closeRegistrationModal();
        }
    }

    public function processRegistrationAction()
    {
        if (!$this->selectedRegistration || !$this->registrationAction) {
            session()->flash('error', 'Please select an action.');
            return;
        }

        if ($this->registrationAction === 'approve') {
            $this->approveRegistration($this->selectedRegistration->id, $this->registrationNotes);
        } elseif ($this->registrationAction === 'reject') {
            $this->rejectRegistration($this->selectedRegistration->id, $this->registrationNotes);
        }
    }

    public function markAsAttended($registrationId)
    {
        $registration = EventRegistration::findOrFail($registrationId);
        
        if (!$this->canManageRegistration($registration)) {
            session()->flash('error', 'You do not have permission to modify this registration.');
            return;
        }

        $registration->update([
            'attended' => true,
            'status' => 'attended',
            'check_in_time' => now()
        ]);

        // Log activity
        activity()
            ->performedOn($registration)
            ->causedBy(auth()->user())
            ->log('marked_as_attended');

        session()->flash('success', 'Participant marked as attended.');
    }

    public function markAsNoShow($registrationId)
    {
        $registration = EventRegistration::findOrFail($registrationId);
        
        if (!$this->canManageRegistration($registration)) {
            session()->flash('error', 'You do not have permission to modify this registration.');
            return;
        }

        $registration->update([
            'status' => 'no_show',
            'attended' => false
        ]);

        // Log activity
        activity()
            ->performedOn($registration)
            ->causedBy(auth()->user())
            ->log('marked_as_no_show');

        session()->flash('success', 'Participant marked as no-show.');
    }

    public function cancelRegistration($registrationId)
    {
        $registration = EventRegistration::findOrFail($registrationId);
        
        if (!$this->canManageRegistration($registration)) {
            session()->flash('error', 'You do not have permission to cancel this registration.');
            return;
        }

        $registration->update([
            'status' => 'cancelled'
        ]);

        // Log activity
        activity()
            ->performedOn($registration)
            ->causedBy(auth()->user())
            ->log('registration_cancelled');

        session()->flash('success', 'Registration cancelled.');
    }

    public function openBulkModal()
    {
        if (empty($this->selectedRegistrations)) {
            session()->flash('error', 'Please select registrations to perform bulk actions.');
            return;
        }

        $this->bulkAction = '';
        $this->bulkApprovalNotes = '';
        $this->showBulkModal = true;
    }

    public function closeBulkModal()
    {
        $this->showBulkModal = false;
        $this->bulkAction = '';
        $this->bulkApprovalNotes = '';
    }

    public function processBulkAction()
    {
        if (empty($this->selectedRegistrations) || !$this->bulkAction) {
            session()->flash('error', 'Please select registrations and an action.');
            return;
        }

        $registrations = EventRegistration::whereIn('id', $this->selectedRegistrations)->get();
        $successCount = 0;
        
        DB::transaction(function () use ($registrations, &$successCount) {
            foreach ($registrations as $registration) {
                if (!$this->canManageRegistration($registration)) {
                    continue;
                }

                switch ($this->bulkAction) {
                    case 'approve':
                        $registration->update([
                            'status' => 'approved',
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                            'approval_notes' => $this->bulkApprovalNotes
                        ]);
                        break;
                        
                    case 'reject':
                        $registration->update([
                            'status' => 'rejected',
                            'approved_at' => now(),
                            'approved_by' => auth()->id(),
                            'approval_notes' => $this->bulkApprovalNotes
                        ]);
                        break;
                        
                    case 'mark_attended':
                        $registration->update([
                            'attended' => true,
                            'status' => 'attended',
                            'check_in_time' => now()
                        ]);
                        break;
                        
                    case 'cancel':
                        $registration->update([
                            'status' => 'cancelled'
                        ]);
                        break;
                }

                // Log activity
                activity()
                    ->performedOn($registration)
                    ->causedBy(auth()->user())
                    ->withProperties(['bulk_action' => $this->bulkAction, 'notes' => $this->bulkApprovalNotes])
                    ->log('bulk_action_' . $this->bulkAction);

                $successCount++;
            }
        });

        $this->selectedRegistrations = [];
        $this->selectAll = false;
        $this->closeBulkModal();
        
        session()->flash('success', $successCount . ' registration(s) updated successfully.');
    }

    public function openExportModal()
    {
        $this->showExportModal = true;
    }

    public function closeExportModal()
    {
        $this->showExportModal = false;
        $this->exportFormat = 'csv';
    }

    public function exportRegistrations()
    {
        $registrations = $this->getFilteredRegistrations()->get();
        
        if ($registrations->isEmpty()) {
            session()->flash('error', 'No registrations to export.');
            return;
        }

        // You can implement export functionality here
        // For now, we'll just close the modal
        $this->closeExportModal();
        session()->flash('success', 'Export functionality will be implemented based on your requirements.');
    }

    private function canManageRegistration($registration)
    {
        $user = auth()->user();
        
        // TRA officers can manage all registrations
        if ($user->role === 'tra_officer') {
            return true;
        }
        
        // Leaders and supervisors can manage registrations for their institution's events
        if (in_array($user->role, ['leader', 'supervisor'])) {
            $userInstitution = $user->members->first()?->institution_id;
            return $registration->event->institution_id === $userInstitution;
        }
        
        // Event creators can manage their event registrations
        return $registration->event->created_by === $user->id;
    }

    private function getFilteredRegistrations()
    {
        $query = EventRegistration::with(['event.institution', 'user', 'approvedBy'])
            ->when($this->search, function($q) {
                $q->where(function($subQuery) {
                    $subQuery->whereHas('user', function($userQuery) {
                        $userQuery->where('name', 'like', '%' . $this->search . '%')
                                 ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('event', function($eventQuery) {
                        $eventQuery->where('title', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->eventFilter, function($q) {
                $q->where('event_id', $this->eventFilter);
            })
            ->when($this->paymentStatusFilter, function($q) {
                $q->where('payment_status', $this->paymentStatusFilter);
            })
            ->when($this->dateRange, function($q) {
                $dates = explode(' to ', $this->dateRange);
                if (count($dates) == 2) {
                    $q->whereBetween('registered_at', [$dates[0], $dates[1]]);
                }
            });

        // Apply user-based filters
        $user = auth()->user();
        if ($user->role !== 'tra_officer') {
            $query->whereHas('event', function($q) use ($user) {
                $q->where(function($subQuery) use ($user) {
                    $subQuery->where('created_by', $user->id);
                    
                    // If user is a member, also include events from their institution
                    if ($user->members->isNotEmpty()) {
                        $subQuery->orWhere('institution_id', $user->members->first()->institution_id);
                    }
                });
            });
        }

        return $query;
    }

    public function render()
    {
        $registrations = $this->getFilteredRegistrations()
            ->orderBy('registered_at', 'desc')
            ->paginate(15);

        // Get events for filter dropdown
        $eventsQuery = Event::where('status', '!=', 'cancelled')
            ->orderBy('start_date', 'desc');
            
        // Apply same user restrictions for events filter
        $user = auth()->user();
        if ($user->role !== 'tra_officer') {
            $eventsQuery->where(function($q) use ($user) {
                $q->where('created_by', $user->id);
                
                if ($user->members->isNotEmpty()) {
                    $q->orWhere('institution_id', $user->members->first()->institution_id);
                }
            });
        }
        
        $events = $eventsQuery->get();

        // Calculate statistics
        $baseQuery = $this->getFilteredRegistrations();
        $stats = [
            'total' => $baseQuery->count(),
            'pending' => $baseQuery->where('status', 'pending')->count(),
            'approved' => $baseQuery->where('status', 'approved')->count(),
            'rejected' => $baseQuery->where('status', 'rejected')->count(),
            'attended' => $baseQuery->where('attended', true)->count(),
            'no_show' => $baseQuery->where('status', 'no_show')->count(),
        ];

        return view('livewire.events.registrations', [
            'registrations' => $registrations,
            'events' => $events,
            'stats' => $stats
        ]);
    }
}