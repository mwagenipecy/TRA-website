<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use App\Models\Institution;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $institutionFilter = '';
    public $dateRange = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'institutionFilter' => ['except' => ''],
        'dateRange' => ['except' => '']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingInstitutionFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->typeFilter = '';
        $this->institutionFilter = '';
        $this->dateRange = '';
        $this->resetPage();
    }

    public function deleteEvent($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Check if user has permission to delete
        if (!auth()->user()->can('delete-event', $event)) {
            session()->flash('error', 'You do not have permission to delete this event.');
            return;
        }

        $event->delete();
        session()->flash('success', 'Event deleted successfully.');
    }

    public function duplicateEvent($eventId)
    {
        $originalEvent = Event::findOrFail($eventId);
        
        $newEvent = $originalEvent->replicate();
        $newEvent->title = $originalEvent->title . ' (Copy)';
        $newEvent->slug = $originalEvent->slug . '-copy-' . time();
        $newEvent->status = 'draft';
        $newEvent->created_by = auth()->id();
        $newEvent->approved_by = null;
        $newEvent->approved_at = null;
        $newEvent->save();

        session()->flash('success', 'Event duplicated successfully.');
        return redirect()->route('events.edit', $newEvent);
    }

    public function render()
    {
        $query = Event::with(['institution', 'creator'])
            ->when($this->search, function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('venue', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->typeFilter, function($q) {
                $q->where('type', $this->typeFilter);
            })
            ->when($this->institutionFilter, function($q) {
                $q->where('institution_id', $this->institutionFilter);
            })
            ->when($this->dateRange, function($q) {
                $dates = explode(' to ', $this->dateRange);
                if (count($dates) == 2) {
                    $q->whereBetween('start_date', [$dates[0], $dates[1]]);
                }
            });

        // Apply user-based filters
        $user = auth()->user();
        if ($user->role !== 'tra_officer') {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('institution_id', $user->members->first()?->institution_id);
            });
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(10);

        $institutions = Institution::where('status', 'active')->get();

        return view('livewire.events.index', [
            'events' => $events,
            'institutions' => $institutions
        ]);
    }
}