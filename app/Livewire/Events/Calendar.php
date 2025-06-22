<?php

namespace App\Livewire\Events;

use Livewire\Component;
use App\Models\Event;
use App\Models\Institution;
use Carbon\Carbon;

class Calendar extends Component
{
    public $currentDate;
    public $currentView = 'month'; // month, week, day
    public $selectedEvent = null;
    public $showEventModal = false;
    
    // Filters
    public $statusFilter = '';
    public $typeFilter = '';
    public $institutionFilter = '';

    public function mount()
    {
        $this->currentDate = now();
    }

    public function previousPeriod()
    {
        switch ($this->currentView) {
            case 'month':
                $this->currentDate = $this->currentDate->subMonth();
                break;
            case 'week':
                $this->currentDate = $this->currentDate->subWeek();
                break;
            case 'day':
                $this->currentDate = $this->currentDate->subDay();
                break;
        }
    }

    public function nextPeriod()
    {
        switch ($this->currentView) {
            case 'month':
                $this->currentDate = $this->currentDate->addMonth();
                break;
            case 'week':
                $this->currentDate = $this->currentDate->addWeek();
                break;
            case 'day':
                $this->currentDate = $this->currentDate->addDay();
                break;
        }
    }

    public function today()
    {
        $this->currentDate = now();
    }

    public function changeView($view)
    {
        $this->currentView = $view;
    }

    public function showEvent($eventId)
    {
        $this->selectedEvent = Event::with(['institution', 'creator', 'registrations'])
            ->findOrFail($eventId);
        $this->showEventModal = true;
    }

    public function closeEventModal()
    {
        $this->showEventModal = false;
        $this->selectedEvent = null;
    }

    public function getCalendarDates()
    {
        if ($this->currentView === 'month') {
            return $this->getMonthDates();
        } elseif ($this->currentView === 'week') {
            return $this->getWeekDates();
        } else {
            return [$this->currentDate];
        }
    }

    private function getMonthDates()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        
        // Get the first day of the calendar (Sunday of the week containing the first day of the month)
        $startDate = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        
        // Get the last day of the calendar (Saturday of the week containing the last day of the month)
        $endDate = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        $dates = [];
        $current = $startDate->copy();
        
        while ($current <= $endDate) {
            $dates[] = $current->copy();
            $current->addDay();
        }
        
        return $dates;
    }

    private function getWeekDates()
    {
        $startOfWeek = $this->currentDate->copy()->startOfWeek(Carbon::SUNDAY);
        $dates = [];
        
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfWeek->copy()->addDays($i);
        }
        
        return $dates;
    }

    public function getEventsForDate($date)
    {
        $query = Event::whereDate('start_date', $date->format('Y-m-d'))
            ->orWhere(function($q) use ($date) {
                $q->whereDate('start_date', '<=', $date->format('Y-m-d'))
                  ->whereDate('end_date', '>=', $date->format('Y-m-d'));
            });

        // Apply filters
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }
        
        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }
        
        if ($this->institutionFilter) {
            $query->where('institution_id', $this->institutionFilter);
        }

        // Apply user-based filters
        $user = auth()->user();
        if ($user->role !== 'tra_officer') {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('institution_id', $user->members->first()?->institution_id);
            });
        }

        return $query->with(['institution'])->get();
    }

    public function render()
    {
        $institutions = Institution::where('status', 'active')->get();
        $calendarDates = $this->getCalendarDates();
        
        return view('livewire.events.calendar', [
            'institutions' => $institutions,
            'calendarDates' => $calendarDates
        ]);
    }
}