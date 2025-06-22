<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates based on permissions
      //  $this->definePermissionGates();
        
        // Define specific role gates
      //  $this->defineRoleGates();
        
        // Define custom authorization logic
     //  $this->defineCustomGates();
    }

    /**
     * Define gates for all permissions
     */
    private function definePermissionGates(): void
    {
        // Get all permissions and create gates
        try {
            $permissions = Permission::all();
            
            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermission($permission->name);
                });
            }
        } catch (\Exception $e) {
            // Handle case where permissions table doesn't exist yet (during migration)
        }
    }

    /**
     * Define role-based gates
     */
    private function defineRoleGates(): void
    {
        // TRA Officer gate
        Gate::define('tra-officer', function ($user) {
            return $user->isTraOfficer();
        });

        // Leader/Supervisor gate
        Gate::define('leader', function ($user) {
            return $user->isLeader();
        });

        // Student gate
        Gate::define('student', function ($user) {
            return $user->isStudent();
        });

        // Institution staff gate (leaders + supervisors)
        Gate::define('institution-staff', function ($user) {
            return $user->hasAnyRole(['leader', 'supervisor']);
        });
    }

    /**
     * Define custom authorization gates
     */
    private function defineCustomGates(): void
    {
        // Can view institution data
        Gate::define('view-institution-data', function ($user, $institution) {
            // TRA officers can view all institutions
            if ($user->isTraOfficer()) {
                return true;
            }

            // Users can view their own institution
            if ($user->member && $user->member->institution_id === $institution->id) {
                return true;
            }

            return false;
        });

        // Can manage institution
        Gate::define('manage-institution', function ($user, $institution) {
            // TRA officers can manage all institutions
            if ($user->isTraOfficer()) {
                return true;
            }

            // Leaders and supervisors can manage their own institution
            if ($user->isLeader() && $user->member && $user->member->institution_id === $institution->id) {
                return $user->hasPermission('manage-institutions');
            }

            return false;
        });

        // Can view member data
        Gate::define('view-member-data', function ($user, $member) {
            // TRA officers can view all members
            if ($user->isTraOfficer()) {
                return true;
            }

            // Users can view their own profile
            if ($user->id === $member->user_id) {
                return true;
            }

            // Institution staff can view members from their institution
            if ($user->isLeader() && $user->member && $user->member->institution_id === $member->institution_id) {
                return $user->hasPermission('view-members');
            }

            return false;
        });

        // Can manage member
        Gate::define('manage-member', function ($user, $member) {
            // TRA officers can manage all members
            if ($user->isTraOfficer()) {
                return true;
            }

            // Leaders and supervisors can manage members from their institution
            if ($user->isLeader() && $user->member && $user->member->institution_id === $member->institution_id) {
                return $user->hasPermission('manage-members');
            }

            return false;
        });

        // Can view event
        Gate::define('view-event', function ($user, $event) {
            // TRA officers can view all events
            if ($user->isTraOfficer()) {
                return true;
            }

            // Users can view events from their institution
            if ($user->member && $user->member->institution_id === $event->institution_id) {
                return $user->hasPermission('view-events');
            }

            // Users can view public events
            if ($event->allow_non_members) {
                return true;
            }

            return false;
        });

        // Can manage event
        Gate::define('manage-event', function ($user, $event) {
            // TRA officers can manage all events
            if ($user->isTraOfficer()) {
                return true;
            }

            // Event creator can manage their own events
            if ($user->id === $event->created_by) {
                return true;
            }

            // Institution leaders can manage events from their institution
            if ($user->isLeader() && $user->member && $user->member->institution_id === $event->institution_id) {
                return $user->hasPermission('manage-events');
            }

            return false;
        });

        // Can view budget
        Gate::define('view-budget', function ($user, $budget) {
            // TRA officers can view all budgets
            if ($user->isTraOfficer()) {
                return true;
            }

            // Budget creator can view their own budgets
            if ($user->id === $budget->created_by) {
                return true;
            }

            // Institution staff can view budgets from their institution
            if ($user->isLeader() && $user->member && $user->member->institution_id === $budget->institution_id) {
                return $user->hasPermission('view-budgets');
            }

            return false;
        });

        // Can manage budget
        Gate::define('manage-budget', function ($user, $budget) {
            // Only TRA officers can manage approved budgets
            if ($budget->status === 'approved' && !$user->isTraOfficer()) {
                return false;
            }

            // TRA officers can manage all budgets
            if ($user->isTraOfficer()) {
                return true;
            }

            // Budget creator can manage their own budgets (if not approved)
            if ($user->id === $budget->created_by && $budget->status !== 'approved') {
                return true;
            }

            // Institution leaders can manage budgets from their institution (if not approved)
            if ($user->isLeader() && $user->member && $user->member->institution_id === $budget->institution_id && $budget->status !== 'approved') {
                return $user->hasPermission('manage-budgets');
            }

            return false;
        });

        // Can approve budget
        Gate::define('approve-budget', function ($user, $budget) {
            // Only TRA officers can approve budgets
            return $user->isTraOfficer() && $user->hasPermission('approve-budgets');
        });

        // Can view reports
        Gate::define('view-institution-reports', function ($user, $institution) {
            // TRA officers can view all reports
            if ($user->isTraOfficer()) {
                return true;
            }

            // Institution staff can view reports from their institution
            if ($user->isLeader() && $user->member && $user->member->institution_id === $institution->id) {
                return $user->hasPermission('view-reports');
            }

            return false;
        });

        // Can access system administration
        Gate::define('system-administration', function ($user) {
            return $user->isTraOfficer() && $user->hasPermission('system-admin');
        });

        // Can impersonate users
        Gate::define('impersonate-user', function ($user, $targetUser) {
            // Only TRA officers with impersonate permission
            if (!$user->isTraOfficer() || !$user->hasPermission('impersonate-users')) {
                return false;
            }

            // Cannot impersonate other TRA officers
            if ($targetUser->isTraOfficer()) {
                return false;
            }

            // Cannot impersonate yourself
            if ($user->id === $targetUser->id) {
                return false;
            }

            return true;
        });
    }
}