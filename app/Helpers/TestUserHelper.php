<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class TestUserHelper
{
    /**
     * Quick login as different user types for testing
     */
    public static function loginAs(string $userType): ?User
    {
        $user = null;

        switch ($userType) {
            case 'tra_officer':
            case 'tra':
                $user = User::whereHas('roles', function ($query) {
                    $query->where('name', 'tra_officer');
                })->first();
                break;

            case 'leader':
                $user = User::whereHas('roles', function ($query) {
                    $query->where('name', 'leader');
                })->first();
                break;

            case 'supervisor':
                $user = User::whereHas('roles', function ($query) {
                    $query->where('name', 'supervisor');
                })->first();
                break;

            case 'student':
                $user = User::whereHas('roles', function ($query) {
                    $query->where('name', 'student');
                })->first();
                break;

            case 'regional_coordinator':
                $user = User::whereHas('roles', function ($query) {
                    $query->where('name', 'regional_coordinator');
                })->first();
                break;

            case 'event_coordinator':
                $user = User::whereHas('roles', function ($query) {
                    $query->where('name', 'event_coordinator');
                })->first();
                break;

            case 'budget_reviewer':
                $user = User::whereHas('roles', function ($query) {
                    $query->where('name', 'budget_reviewer');
                })->first();
                break;
        }

        if ($user) {
            Auth::login($user);
            return $user;
        }

        return null;
    }

    /**
     * Get all test users by role
     */
    public static function getTestUsers(): array
    {
        return [
            'TRA Officers' => User::whereHas('roles', function ($query) {
                $query->where('name', 'tra_officer');
            })->get(['name', 'email']),
            
            'Leaders' => User::whereHas('roles', function ($query) {
                $query->where('name', 'leader');
            })->with('member.institution')->get(['name', 'email']),
            
            'Supervisors' => User::whereHas('roles', function ($query) {
                $query->where('name', 'supervisor');
            })->with('member.institution')->get(['name', 'email']),
            
            'Students' => User::whereHas('roles', function ($query) {
                $query->where('name', 'student');
            })->with('member.institution')->take(10)->get(['name', 'email']),
            
            'Custom Roles' => User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['regional_coordinator', 'event_coordinator', 'budget_reviewer']);
            })->with('roles')->get(['name', 'email']),
        ];
    }

    /**
     * Check user permissions for testing
     */
    public static function checkUserPermissions(User $user): array
    {
        $allPermissions = \App\Models\Permission::pluck('name')->toArray();
        $userPermissions = [];

        foreach ($allPermissions as $permission) {
            $userPermissions[$permission] = $user->hasPermission($permission);
        }

        return [
            'user' => $user->name . ' (' . $user->email . ')',
            'roles' => $user->roles->pluck('display_name')->toArray(),
            'permissions' => $userPermissions,
            'total_permissions' => count(array_filter($userPermissions)),
        ];
    }

    /**
     * Get login URLs for quick testing
     */
    public static function getTestLoginInfo(): array
    {
        return [
            'Base URL' => config('app.url'),
            'Login URL' => route('login'),
            'Dashboard URL' => route('dashboard'),
            'Default Password' => 'password123',
            'Test Users' => [
                'TRA Officer' => 'amina.hassan@tra.go.tz',
                'UDSM Leader' => 'peter.mwangoka@udsm.ac.tz',
                'UDSM Supervisor' => 'mary.ndumbaro@udsm.ac.tz',
                'Student' => 'alice.mwamba@student.udsm.ac.tz',
            ],
        ];
    }

    /**
     * Generate sample data for testing dashboard
     */
    public static function generateSampleActivities(int $count = 10): array
    {
        $activities = [];
        $types = [
            'user_registered', 'event_created', 'event_registered', 
            'budget_submitted', 'budget_approved', 'member_approved'
        ];
        
        $institutions = \App\Models\Institution::pluck('name')->toArray();
        $users = User::pluck('name')->toArray();

        for ($i = 0; $i < $count; $i++) {
            $activities[] = [
                'type' => $types[array_rand($types)],
                'description' => $this->generateActivityDescription($types[array_rand($types)]),
                'user' => ['name' => $users[array_rand($users)]],
                'institution' => ['name' => $institutions[array_rand($institutions)]],
                'performed_at' => now()->subMinutes(rand(1, 1440))->toISOString(),
            ];
        }

        return $activities;
    }

    private static function generateActivityDescription(string $type): string
    {
        return match ($type) {
            'user_registered' => 'New user registered for tax club membership',
            'event_created' => 'New tax awareness workshop was created',
            'event_registered' => 'User registered for upcoming seminar',
            'budget_submitted' => 'Budget proposal submitted for review',
            'budget_approved' => 'Annual budget proposal was approved',
            'member_approved' => 'New member application was approved',
            default => 'System activity occurred',
        };
    }
}