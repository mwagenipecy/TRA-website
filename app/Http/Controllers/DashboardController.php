<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Institution;
use App\Models\Member;
use App\Models\Event;
use App\Models\Budget;
use App\Models\Activity;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get role-specific data
        $data = $this->getDashboardData($user);
        
        return view('dashboard', $data);
    }

    /**
     * Get dashboard data based on user role
     */
    private function getDashboardData(User $user): array
    {
        $baseData = [
            'user' => $user,
            'recentActivities' => $this->getRecentActivities($user),
            'upcomingEvents' => $this->getUpcomingEvents($user),
        ];

        // Use the new role system
        if ($user->isTraOfficer()) {
            return array_merge($baseData, $this->getTraOfficerData());
        } elseif ($user->isLeader()) {
            return array_merge($baseData, $this->getLeaderData($user));
        } elseif ($user->isStudent()) {
            return array_merge($baseData, $this->getStudentData($user));
        }

        return $baseData;
    }

    /**
     * Get data for TRA Officers (System-wide overview)
     */
    private function getTraOfficerData(): array
    {
        $currentYear = date('Y');
        $currentMonth = date('m');
        $lastMonth = date('m', strtotime('-1 month'));
        
        // Basic statistics
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

        // Budget statistics
        $totalBudgetAmount = Budget::where('status', 'approved')
            ->where('financial_year', $currentYear)
            ->sum('approved_amount');
        
        $spentAmount = Budget::where('status', 'approved')
            ->where('financial_year', $currentYear)
            ->sum('spent_amount');

        // Institution statistics by region
        $institutionsByRegion = Institution::active()
            ->select('region', DB::raw('count(*) as count'))
            ->groupBy('region')
            ->orderBy('count', 'desc')
            ->get();

        // Top performing institutions (by member count)
        $topInstitutions = Institution::active()
            ->withCount('activeMembers')
            ->orderBy('active_members_count', 'desc')
            ->take(5)
            ->get();

        // Recent approvals needed
        $pendingInstitutions = Institution::pending()->count();
        $pendingMembers = Member::pending()->count();
        
        // Monthly event statistics
        $monthlyEvents = Event::select(
                DB::raw('MONTH(start_date) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('start_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'stats' => [
                'total_members' => $totalMembers,
                'total_institutions' => $totalInstitutions,
                'active_events' => $activeEvents,
                'pending_budgets' => $pendingBudgets,
                'member_growth' => $memberGrowth,
                'total_budget_amount' => $totalBudgetAmount,
                'spent_amount' => $spentAmount,
                'budget_utilization' => $totalBudgetAmount > 0 ? round(($spentAmount / $totalBudgetAmount) * 100, 1) : 0,
                'pending_institutions' => $pendingInstitutions,
                'pending_members' => $pendingMembers,
            ],
            'charts' => [
                'institutions_by_region' => $institutionsByRegion,
                'monthly_events' => $monthlyEvents,
                'top_institutions' => $topInstitutions,
            ],
            'pendingApprovals' => $this->getPendingApprovals(),
        ];
    }

    /**
     * Get data for Leaders and Supervisors (Institution-specific)
     */
    private function getLeaderData(User $user): array
    {
        $member = $user->member;
        if (!$member || !$member->institution) {
            return [];
        }

        $institution = $member->institution;
        $currentYear = date('Y');

        // Institution statistics
        $institutionMembers = $institution->activeMembers()->count();
        $pendingMembers = $institution->pendingMembers()->count();
        $institutionEvents = $institution->events()
            ->where('start_date', '>', now())
            ->where('status', 'published')
            ->count();
        
        // Budget statistics for the institution
        $institutionBudgets = $institution->budgets()
            ->where('financial_year', $currentYear)
            ->count();
        $approvedBudgets = $institution->approvedBudgets()
            ->where('financial_year', $currentYear)
            ->count();

        // Recent member registrations
        $recentRegistrations = $institution->members()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        // Upcoming events for the institution
        $institutionUpcomingEvents = $institution->upcomingEvents()
            ->take(5)
            ->get();

        // Event attendance statistics
        $eventAttendanceStats = $institution->events()
            ->withCount(['registrations as total_registrations', 'registrations as attended_count' => function ($query) {
                $query->where('attended', true);
            }])
            ->where('status', 'completed')
            ->take(5)
            ->get();

        return [
            'institution' => $institution,
            'stats' => [
                'institution_members' => $institutionMembers,
                'pending_members' => $pendingMembers,
                'institution_events' => $institutionEvents,
                'institution_budgets' => $institutionBudgets,
                'approved_budgets' => $approvedBudgets,
            ],
            'recent_registrations' => $recentRegistrations,
            'institution_upcoming_events' => $institutionUpcomingEvents,
            'event_attendance_stats' => $eventAttendanceStats,
        ];
    }

    /**
     * Get data for Students (Personal activity overview)
     */
    private function getStudentData(User $user): array
    {
        $member = $user->member;
        if (!$member) {
            return [];
        }

        // Personal statistics
        $eventsAttended = $user->eventRegistrations()
            ->where('attended', true)
            ->count();
        
        $eventsRegistered = $user->eventRegistrations()
            ->whereIn('status', ['approved', 'pending'])
            ->count();

        $upcomingRegistrations = $user->eventRegistrations()
            ->with('event')
            ->whereHas('event', function ($query) {
                $query->where('start_date', '>', now());
            })
            ->whereIn('status', ['approved', 'pending'])
            ->get();

        // Recent certificates (if implemented)
        // $recentCertificates = $user->certificates()->latest()->take(3)->get();

        // Personal activity timeline
        $personalActivities = Activity::where('user_id', $user->id)
            ->latest('performed_at')
            ->take(10)
            ->get();

        return [
            'member' => $member,
            'stats' => [
                'events_attended' => $eventsAttended,
                'events_registered' => $eventsRegistered,
                'upcoming_events' => $upcomingRegistrations->count(),
            ],
            'upcoming_registrations' => $upcomingRegistrations,
            'personal_activities' => $personalActivities,
        ];
    }

    /**
     * Get recent activities based on user role
     */
    private function getRecentActivities(User $user, int $limit = 10): array
    {
        $query = Activity::with(['user', 'institution'])
            ->latest('performed_at');

        // Filter activities based on user role and permissions
        if ($user->isTraOfficer()) {
            // TRA officers see all activities
        } elseif ($user->isLeader()) {
            // Leaders see institution-specific activities
            if ($user->member && $user->member->institution) {
                $query->where('institution_id', $user->member->institution_id);
            }
        } else {
            // Students see their own activities and institution-wide activities
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id);
                if ($user->member && $user->member->institution) {
                    $q->orWhere('institution_id', $user->member->institution_id);
                }
            });
        }

        return $query->take($limit)->get()->toArray();
    }

    /**
     * Get upcoming events based on user role
     */
    private function getUpcomingEvents(User $user, int $limit = 5): array
    {
        $query = Event::with(['institution', 'creator'])
            ->where('start_date', '>', now())
            ->where('status', 'published')
            ->orderBy('start_date');

        // Filter events based on user role and permissions
        if ($user->isTraOfficer()) {
            // TRA officers see all events
        } elseif ($user->member && $user->member->institution) {
            // Members see their institution's events
            $query->where('institution_id', $user->member->institution_id);
        }

        return $query->take($limit)->get()->toArray();
    }

    /**
     * Get pending approvals for TRA officers
     */
    private function getPendingApprovals(): array
    {
        return [
            'budgets' => Budget::with(['institution', 'creator'])
                ->where('status', 'submitted')
                ->orderBy('created_at')
                ->take(5)
                ->get(),
            'institutions' => Institution::with('creator')
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->take(5)
                ->get(),
            'members' => Member::with(['user', 'institution'])
                ->where('status', 'pending')
                ->orderBy('created_at')
                ->take(5)
                ->get(),
        ];
    }

    /**
     * API endpoint for dashboard statistics
     */
    public function getStats(Request $request)
    {
        $user = Auth::user();
        $data = $this->getDashboardData($user);
        
        return response()->json([
            'stats' => $data['stats'] ?? [],
            'charts' => $data['charts'] ?? [],
        ]);
    }

    /**
     * API endpoint for recent activities
     */
    public function getActivities(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 10);
        
        return response()->json([
            'activities' => $this->getRecentActivities($user, $limit),
        ]);
    }

    /**
     * Global search endpoint
     */
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');
        $user = Auth::user();
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];

        // Search members
        if ($user->hasPermission('view-members')) {
            $members = User::search($query)
                ->whereHas('member')
                ->take(5)
                ->get(['id', 'name', 'email']);
            
            foreach ($members as $member) {
                $results[] = [
                    'type' => 'member',
                    'id' => $member->id,
                    'title' => $member->name,
                    'subtitle' => $member->email,
                    'url' => route('members.show', $member->id),
                ];
            }
        }

        // Search events
        if ($user->hasPermission('view-events')) {
            $events = Event::where('title', 'like', "%{$query}%")
                ->published()
                ->take(5)
                ->get(['id', 'title', 'start_date']);
            
            foreach ($events as $event) {
                $results[] = [
                    'type' => 'event',
                    'id' => $event->id,
                    'title' => $event->title,
                    'subtitle' => $event->start_date->format('M d, Y'),
                    'url' => route('events.show', $event->id),
                ];
            }
        }

        // Search institutions
        if ($user->hasPermission('view-institutions')) {
            $institutions = Institution::search($query)
                ->active()
                ->take(5)
                ->get(['id', 'name', 'city']);
            
            foreach ($institutions as $institution) {
                $results[] = [
                    'type' => 'institution',
                    'id' => $institution->id,
                    'title' => $institution->name,
                    'subtitle' => $institution->city,
                    'url' => route('institutions.show', $institution->id),
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}