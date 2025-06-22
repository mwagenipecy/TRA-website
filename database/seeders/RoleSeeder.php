<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define role permissions
        $roles = [
            [
                'name' => 'tra_officer',
                'display_name' => 'TRA Officer',
                'description' => 'Tanzania Revenue Authority Officer with full system access',
                'is_system_role' => true,
                'permissions' => [], // TRA Officers get all permissions automatically
            ],
            [
                'name' => 'leader',
                'display_name' => 'Club Leader',
                'description' => 'Tax club leader at an institution',
                'is_system_role' => true,
                'permissions' => [
                    // Institution management (own institution only)
                    'view-institutions',
                    'manage-institutions',
                    
                    // Member management
                    'view-members',
                    'manage-members',
                    'approve-members',
                    'promote-members',
                    'suspend-members',
                    
                    // Event management
                    'view-events',
                    'manage-events',
                    'manage-event-registrations',
                    
                    // Budget management
                    'view-budgets',
                    'manage-budgets',
                    'manage-budget-expenses',
                    
                    // Certificate management
                    'view-certificates',
                    'manage-certificates',
                    
                    // Reporting
                    'view-reports',
                    'generate-reports',
                    'export-data',
                    
                    // Communication
                    'send-notifications',
                ],
            ],
            [
                'name' => 'supervisor',
                'display_name' => 'Club Supervisor',
                'description' => 'Senior academic staff supervising tax club activities',
                'is_system_role' => true,
                'permissions' => [
                    // Institution management (view only)
                    'view-institutions',
                    
                    // Member management
                    'view-members',
                    'manage-members',
                    'approve-members',
                    
                    // Event management
                    'view-events',
                    'manage-events',
                    'manage-event-registrations',
                    
                    // Budget management
                    'view-budgets',
                    'review-budgets',
                    
                    // Certificate management
                    'view-certificates',
                    'manage-certificates',
                    
                    // Reporting
                    'view-reports',
                    'generate-reports',
                    
                    // Communication
                    'send-notifications',
                ],
            ],
            [
                'name' => 'student',
                'display_name' => 'Student Member',
                'description' => 'Student member of a tax club',
                'is_system_role' => true,
                'permissions' => [
                    // Basic viewing permissions
                    'view-events',
                    'view-certificates',
                    
                    // Can view their own institution
                    'view-institutions',
                    
                    // Limited member viewing (only club members)
                    'view-members',
                    
                    // Basic reporting (own activities)
                    'view-reports',
                ],
            ],
            [
                'name' => 'guest',
                'display_name' => 'Guest User',
                'description' => 'Limited access for non-members',
                'is_system_role' => true,
                'permissions' => [
                    // Very limited permissions
                    'view-events',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::updateOrCreate(
                ['name' => $roleData['name']],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                    'is_system_role' => $roleData['is_system_role'],
                    'permissions' => $roleData['permissions'],
                ]
            );
        }

        // Create additional custom roles for testing
        $customRoles = [
            [
                'name' => 'regional_coordinator',
                'display_name' => 'Regional Coordinator',
                'description' => 'Coordinates tax club activities within a specific region',
                'is_system_role' => false,
                'permissions' => [
                    'view-institutions',
                    'view-members',
                    'view-events',
                    'view-budgets',
                    'view-reports',
                    'generate-reports',
                    'send-notifications',
                ],
            ],
            [
                'name' => 'event_coordinator',
                'display_name' => 'Event Coordinator',
                'description' => 'Specialized role for managing events across institutions',
                'is_system_role' => false,
                'permissions' => [
                    'view-events',
                    'manage-events',
                    'manage-event-registrations',
                    'view-members',
                    'send-notifications',
                    'view-reports',
                ],
            ],
            [
                'name' => 'budget_reviewer',
                'display_name' => 'Budget Reviewer',
                'description' => 'Reviews budget proposals before TRA approval',
                'is_system_role' => false,
                'permissions' => [
                    'view-budgets',
                    'review-budgets',
                    'view-institutions',
                    'view-reports',
                    'generate-reports',
                ],
            ],
        ];

        foreach ($customRoles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}