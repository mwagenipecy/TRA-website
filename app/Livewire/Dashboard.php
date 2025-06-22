<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use App\Models\Institution;
use App\Models\Member;
use App\Models\Event;
use App\Models\Budget;
use App\Models\Activity;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $user;
    public $refreshInterval = 30000; // 30 seconds
    public $lastUpdated;
    
    // Dashboard data properties
    public $stats = [];
    public $recentActivities = [];
    public $upcomingEvents = [];
    public $pendingApprovals = [];
    public $charts = [];
    
    // Filter properties
    public $activityFilter = 'all';
    public $dateRange = '7days';
    public $showOnlyMyActivities = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadDashboardData();
        $this->lastUpdated = now();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }

    /**
     * Load dashboard data based on user role
     */
    public function loadDashboardData()
    {
        $this->stats = $this->getStats();
        $this->recentActivities = $this->getRecentActivities();
        $this->upcomingEvents = $this->getUpcomingEvents();
        
        if ($this->user->isTraOfficer()) {
            $this->pendingApprovals = $this->getPendingApprovals();
            $this->charts = $this->getChartData();
        }
        
        $this->lastUpdated = now();
    }

    /**
     * Get statistics based on user role
     */
    private function getStats(): array
    {
        return match ($this->user->role) {
            'tra_officer' => $this->getTraOfficerStats(),
            'leader', 'supervisor' => $this->getLeaderStats(),
            'student' => $this->getStudentStats(),
            default => [],
        };
    }

    /**
     * Get TRA Officer statistics
     */
    private function getTraOfficerStats(): array
    {
        $currentMonth = now()->month;
        $lastMonth = now()->subMonth()->month;
        
        $totalMembers = Member::active()->count();
        $totalInstitutions = Institution::active()->count();
        $activeEvents = Event::where('status', 'published')
            ->where('start_date', '>', now())
            ->count();
        $pendingBudgets = Budget::where('status', 'submitted')->count();

        // Growth calculations
        $lastMonthMembers = Member::active()
            ->whereMonth('created_at', $lastMonth)
            ->count();
        $currentMonthMembers = Member::active()
            ->whereMonth('created_at', $currentMonth)
            ->count();
        
        $memberGrowth = $lastMonthMembers > 0 
            ? round((($currentMonthMembers - $lastMonthMembers) / $lastMonthMembers) * 100, 1)
            : 0;

        return [
            'total_members' => $totalMembers,
            'total_institutions' => $totalInstitutions,
            'active_events' => $activeEvents,
            'pending_budgets' => $pendingBudgets,
            'member_growth' => $memberGrowth,
            'pending_institutions' => Institution::pending()->count(),
            'pending_members' => Member::pending()->count(),
        ];
    }

    /**
     * Get Leader/Supervisor statistics
     */
    private function getLeaderStats(): array
    {
        $member = $this->user->member;
        if (!$member || !$member->institution) {
            return [];
        }

        $institution = $member->institution;
        
        return [
            'institution_members' => $institution->activeMembers()->count(),
            'pending_members' => $institution->pendingMembers()->count(),
            'institution_events' => $institution->upcomingEvents()->count(),
            'pending_budgets' => $institution->pendingBudgets()->count(),
        ];
    }

    /**
     * Get Student statistics
     */
    private function getStudentStats(): array
    {
        return [
            'events_attended' => $this->user->eventRegistrations()
                ->where('attended', true)
                ->count(),
            'events_registered' => $this->user->eventRegistrations()
                ->whereIn('status', ['approved', 'pending'])
                ->count(),
            'upcoming_events' => $this->user->eventRegistrations()
                ->whereHas('event', function ($query) {
                    $query->where('start_date', '>', now());
                })
                ->whereIn('status', ['approved', 'pending'])
                ->count(),
        ];
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities(): array
    {
        $query = Activity::with(['user', 'institution'])
            ->latest('performed_at');

        // Apply date range filter
        $query = $this->applyDateRangeFilter($query);

        // Filter based on user role and preferences
        if ($this->showOnlyMyActivities) {
            $query->where('user_id', $this->user->id);
        } elseif ($this->user->role === 'tra_officer') {
            // TRA officers see all activities
        } elseif (in_array($this->user->role, ['leader', 'supervisor'])) {
            // Leaders see institution-specific activities
            if ($this->user->member && $this->user->member->institution) {
                $query->where('institution_id', $this->user->member->institution_id);
            }
        } else {
            // Students see their own activities and institution-wide activities
            $query->where(function ($q) {
                $q->where('user_id', $this->user->id);
                if ($this->user->member && $this->user->member->institution) {
                    $q->orWhere('institution_id', $this->user->member->institution_id);
                }
            });
        }

        // Apply activity type filter
        if ($this->activityFilter !== 'all') {
            $query->where('type', $this->activityFilter);
        }

        return $query->take(10)->get()->toArray();
    }

    /**
     * Get upcoming events
     */
    private function getUpcomingEvents(): array
    {
        $query = Event::with(['institution', 'creator'])
            ->where('start_date', '>', now())
            ->where('status', 'published')
            ->orderBy('start_date');

        // Filter based on user role
        if ($this->user->role !== 'tra_officer' && $this->user->member && $this->user->member->institution) {
            $query->where('institution_id', $this->user->member->institution_id);
        }

        return $query->take(5)->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start_date' => $event->start_date,
                'venue' => $event->venue,
                'institution' => $event->institution->name,
                'registrations_count' => $event->registrations()->count(),
            ];
        })->toArray();
    }

    /**
     * Get pending approvals (TRA Officers only)
     */
    private function getPendingApprovals(): array
    {
        if (!$this->user->isTraOfficer()) {
            return [];
        }

        return [
            'budgets' => Budget::with(['institution', 'creator'])
                ->where('status', 'submitted')
                ->orderBy('created_at')
                ->take(5)
                ->get()
                ->map(function ($budget) {
                    return [
                        'id' => $budget->id,
                        'title' => $budget->title,
                        'institution' => $budget->institution->name,
                        'amount' => $budget->total_amount,
                        'created_at' => $budget->created_at,
                    ];
                })->toArray(),
            'institutions' => Institution::with('creator')
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->take(5)
                ->get()
                ->map(function ($institution) {
                    return [
                        'id' => $institution->id,
                        'name' => $institution->name,
                        'type' => $institution->type,
                        'city' => $institution->city,
                        'created_at' => $institution->created_at,
                    ];
                })->toArray(),
            'members' => Member::with(['user', 'institution'])
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->take(5)
                ->get()
                ->map(function ($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->user->name,
                        'institution' => $member->institution->name,
                        'created_at' => $member->created_at,
                    ];
                })->toArray(),
        ];
    }

    /**
     * Get chart data (TRA Officers only)
     */
    private function getChartData(): array
    {
        if (!$this->user->isTraOfficer()) {
            return [];
        }

        return [
            'institutions_by_region' => Institution::active()
                ->select('region', DB::raw('count(*) as count'))
                ->groupBy('region')
                ->orderBy('count', 'desc')
                ->get()
                ->toArray(),
            'monthly_registrations' => Member::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->toArray(),
            'event_types' => Event::select('type', DB::raw('count(*) as count'))
                ->whereYear('created_at', date('Y'))
                ->groupBy('type')
                ->orderBy('count', 'desc')
                ->get()
                ->toArray(),
        ];
    }

    /**
     * Apply date range filter to query
     */
    private function applyDateRangeFilter($query)
    {
        return match ($this->dateRange) {
            '1day' => $query->where('performed_at', '>=', now()->subDay()),
            '7days' => $query->where('performed_at', '>=', now()->subDays(7)),
            '30days' => $query->where('performed_at', '>=', now()->subDays(30)),
            '90days' => $query->where('performed_at', '>=', now()->subDays(90)),
            default => $query,
        };
    }

    /**
     * Refresh dashboard data
     */
    #[On('refresh-dashboard')]
    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('dashboard-refreshed');
    }

    /**
     * Update activity filter
     */
    public function updatedActivityFilter()
    {
        $this->recentActivities = $this->getRecentActivities();
    }

    /**
     * Update date range filter
     */
    public function updatedDateRange()
    {
        $this->recentActivities = $this->getRecentActivities();
    }

    /**
     * Toggle show only my activities
     */
    public function updatedShowOnlyMyActivities()
    {
        $this->recentActivities = $this->getRecentActivities();
    }

    /**
     * Quick approve budget (TRA Officers only)
     */
    public function quickApproveBudget($budgetId)
    {
        if (!$this->user->isTraOfficer()) {
            return;
        }

        $budget = Budget::find($budgetId);
        if ($budget && $budget->status === 'submitted') {
            $budget->update([
                'status' => 'approved',
                'approved_by' => $this->user->id,
                'approved_at' => now(),
                'approved_amount' => $budget->total_amount,
            ]);

            // Log activity
            Activity::create([
                'type' => 'budget_approved',
                'description' => "Budget '{$budget->title}' was approved",
                'user_id' => $this->user->id,
                'institution_id' => $budget->institution_id,
                'subject_type' => Budget::class,
                'subject_id' => $budget->id,
                'performed_at' => now(),
            ]);

            $this->loadDashboardData();
            session()->flash('success', 'Budget approved successfully!');
        }
    }

    /**
     * Quick reject budget (TRA Officers only)
     */
    public function quickRejectBudget($budgetId, $reason = null)
    {
        if (!$this->user->isTraOfficer()) {
            return;
        }

        $budget = Budget::find($budgetId);
        if ($budget && $budget->status === 'submitted') {
            $budget->update([
                'status' => 'rejected',
                'reviewed_by' => $this->user->id,
                'reviewed_at' => now(),
                'rejection_reason' => $reason ?? 'Quick rejection from dashboard',
            ]);

            // Log activity
            Activity::create([
                'type' => 'budget_rejected',
                'description' => "Budget '{$budget->title}' was rejected",
                'user_id' => $this->user->id,
                'institution_id' => $budget->institution_id,
                'subject_type' => Budget::class,
                'subject_id' => $budget->id,
                'performed_at' => now(),
            ]);

            $this->loadDashboardData();
            session()->flash('warning', 'Budget rejected.');
        }
    }

    /**
     * Register for event (Students)
     */
    public function registerForEvent($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            return;
        }

        // Check if already registered
        $existingRegistration = EventRegistration::where('event_id', $eventId)
            ->where('user_id', $this->user->id)
            ->first();

        if ($existingRegistration) {
            session()->flash('warning', 'You are already registered for this event.');
            return;
        }

        // Create registration
        EventRegistration::create([
            'event_id' => $eventId,
            'user_id' => $this->user->id,
            'is_member' => $this->user->member ? true : false,
            'status' => $event->requires_approval ? 'pending' : 'approved',
            'registered_at' => now(),
        ]);

        // Log activity
        Activity::create([
            'type' => 'event_registered',
            'description' => "Registered for event '{$event->title}'",
            'user_id' => $this->user->id,
            'institution_id' => $event->institution_id,
            'subject_type' => Event::class,
            'subject_id' => $event->id,
            'performed_at' => now(),
        ]);

        $this->loadDashboardData();
        session()->flash('success', 'Successfully registered for the event!');
    }

    /**
     * Get activity type options for filter
     */
    public function getActivityTypeOptions(): array
    {
        return [
            'all' => 'All Activities',
            'user_registered' => 'User Registrations',
            'event_created' => 'Events Created',
            'event_registered' => 'Event Registrations',
            'budget_submitted' => 'Budget Submissions',
            'budget_approved' => 'Budget Approvals',
            'member_approved' => 'Member Approvals',
            'institution_approved' => 'Institution Approvals',
        ];
    }



    public function getUserRoleDisplay()
    {
        // Example implementation - adjust based on your user model structure
        $user = auth()->user();
        
        if (!$user) {
            return 'Guest';
        }
        
        // If you have a role relationship
        if ($user->role) {
            return $user->role; // or however your role is structured
        }
        
        // If role is stored as a string field
        return $user->role ?? 'User';
        
        // Or if you have multiple roles
        // return $user->roles->pluck('name')->join(', ');
    }
    
    public function getUserInstitution()
    {
        // Similarly, add this method if it doesn't exist
        $user = auth()->user();
        
        if (!$user) {
            return 'No Institution';
        }
        
        return $user->institution->name ?? 'No Institution';
    }



    public function getActivityIconColor($activity)
{
    // Example implementation - adjust based on your activity structure
    
    // If activity has a status field
    if (isset($activity->status)) {
        return match($activity->status) {
            'completed' => 'text-green-500',
            'pending' => 'text-yellow-500',
            'failed', 'error' => 'text-red-500',
            'in_progress' => 'text-blue-500',
            default => 'text-gray-500'
        };
    }
    
    // If activity has a type field
    if (isset($activity->type)) {
        return match($activity->type) {
            'login' => 'text-green-500',
            'logout' => 'text-gray-500',
            'create' => 'text-blue-500',
            'update' => 'text-yellow-500',
            'delete' => 'text-red-500',
            'view' => 'text-purple-500',
            default => 'text-gray-400'
        };
    }
    
    // If activity has a priority field
    if (isset($activity->priority)) {
        return match($activity->priority) {
            'high' => 'text-red-500',
            'medium' => 'text-yellow-500',
            'low' => 'text-green-500',
            default => 'text-gray-500'
        };
    }
    
    // Default fallback
    return 'text-gray-500';
}




public function getActivityIcon($activity)
{
    // Example implementation - adjust based on your activity structure
    
    // If activity has a type field
    if (isset($activity->type)) {
        return match($activity->type) {
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'create' => 'fas fa-plus-circle',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'view' => 'fas fa-eye',
            'download' => 'fas fa-download',
            'upload' => 'fas fa-upload',
            'email' => 'fas fa-envelope',
            'notification' => 'fas fa-bell',
            'payment' => 'fas fa-credit-card',
            'security' => 'fas fa-shield-alt',
            'profile' => 'fas fa-user',
            'settings' => 'fas fa-cog',
            default => 'fas fa-circle'
        };
    }
    
    // If activity has a status field
    if (isset($activity->status)) {
        return match($activity->status) {
            'completed', 'success' => 'fas fa-check-circle',
            'pending' => 'fas fa-clock',
            'failed', 'error' => 'fas fa-exclamation-circle',
            'in_progress' => 'fas fa-spinner',
            'cancelled' => 'fas fa-times-circle',
            default => 'fas fa-info-circle'
        };
    }
    
    // If activity has an action field
    if (isset($activity->action)) {
        return match($activity->action) {
            'created' => 'fas fa-plus',
            'updated' => 'fas fa-edit',
            'deleted' => 'fas fa-trash',
            'viewed' => 'fas fa-eye',
            'shared' => 'fas fa-share',
            'commented' => 'fas fa-comment',
            'liked' => 'fas fa-heart',
            'followed' => 'fas fa-user-plus',
            default => 'fas fa-activity'
        };
    }
    
    // Default fallback
    return 'fas fa-circle';
}




public function getQuickActions()
{
    return [
        [
            'title' => 'Create New User',
            'description' => 'Add a new user to the system',
            'icon' => 'fas fa-user-plus',
            'color' => 'blue',
            'route' => 'institutions.create'
        ],
        [
            'title' => 'View Reports',
            'description' => 'Access system reports',
            'icon' => 'fas fa-chart-bar',
            'color' => 'green',
            'route' => 'institutions.index'
        ],
      
       
    
    ];
}



    /**
     * Get date range options for filter
     */
    public function getDateRangeOptions(): array
    {
        return [
            '1day' => 'Last 24 Hours',
            '7days' => 'Last 7 Days',
            '30days' => 'Last 30 Days',
            '90days' => 'Last 90 Days',
            'all' => 'All Time',
        ];
    }
}