<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Institution Management
            [
                'name' => 'view-institutions',
                'display_name' => 'View Institutions',
                'description' => 'Can view list of institutions and their details',
                'category' => 'institutions',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-institutions',
                'display_name' => 'Manage Institutions',
                'description' => 'Can create, edit, and manage institutions',
                'category' => 'institutions',
                'is_system_permission' => true,
            ],
            [
                'name' => 'approve-institutions',
                'display_name' => 'Approve Institutions',
                'description' => 'Can approve or reject institution applications',
                'category' => 'institutions',
                'is_system_permission' => true,
            ],
            [
                'name' => 'delete-institutions',
                'display_name' => 'Delete Institutions',
                'description' => 'Can delete institutions (dangerous permission)',
                'category' => 'institutions',
                'is_system_permission' => true,
            ],

            // Member Management
            [
                'name' => 'view-members',
                'display_name' => 'View Members',
                'description' => 'Can view list of members and their details',
                'category' => 'members',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-members',
                'display_name' => 'Manage Members',
                'description' => 'Can add, edit, and manage members',
                'category' => 'members',
                'is_system_permission' => true,
            ],
            [
                'name' => 'approve-members',
                'display_name' => 'Approve Members',
                'description' => 'Can approve or reject member applications',
                'category' => 'members',
                'is_system_permission' => true,
            ],
            [
                'name' => 'promote-members',
                'display_name' => 'Promote Members',
                'description' => 'Can promote members to leadership roles',
                'category' => 'members',
                'is_system_permission' => true,
            ],
            [
                'name' => 'suspend-members',
                'display_name' => 'Suspend Members',
                'description' => 'Can suspend or deactivate members',
                'category' => 'members',
                'is_system_permission' => true,
            ],

            // Event Management
            [
                'name' => 'view-events',
                'display_name' => 'View Events',
                'description' => 'Can view events and their details',
                'category' => 'events',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-events',
                'display_name' => 'Manage Events',
                'description' => 'Can create, edit, and manage events',
                'category' => 'events',
                'is_system_permission' => true,
            ],
            [
                'name' => 'approve-events',
                'display_name' => 'Approve Events',
                'description' => 'Can approve or reject events',
                'category' => 'events',
                'is_system_permission' => true,
            ],
            [
                'name' => 'cancel-events',
                'display_name' => 'Cancel Events',
                'description' => 'Can cancel events',
                'category' => 'events',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-event-registrations',
                'display_name' => 'Manage Event Registrations',
                'description' => 'Can manage event registrations and attendance',
                'category' => 'events',
                'is_system_permission' => true,
            ],

            // Budget Management
            [
                'name' => 'view-budgets',
                'display_name' => 'View Budgets',
                'description' => 'Can view budget proposals and details',
                'category' => 'budgets',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-budgets',
                'display_name' => 'Manage Budgets',
                'description' => 'Can create and edit budget proposals',
                'category' => 'budgets',
                'is_system_permission' => true,
            ],
            [
                'name' => 'approve-budgets',
                'display_name' => 'Approve Budgets',
                'description' => 'Can approve or reject budget proposals',
                'category' => 'budgets',
                'is_system_permission' => true,
            ],
            [
                'name' => 'review-budgets',
                'display_name' => 'Review Budgets',
                'description' => 'Can review and comment on budget proposals',
                'category' => 'budgets',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-budget-expenses',
                'display_name' => 'Manage Budget Expenses',
                'description' => 'Can record and track budget expenses',
                'category' => 'budgets',
                'is_system_permission' => true,
            ],

            // Certificate Management
            [
                'name' => 'view-certificates',
                'display_name' => 'View Certificates',
                'description' => 'Can view certificates',
                'category' => 'certificates',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-certificates',
                'display_name' => 'Manage Certificates',
                'description' => 'Can issue and manage certificates',
                'category' => 'certificates',
                'is_system_permission' => true,
            ],
            [
                'name' => 'revoke-certificates',
                'display_name' => 'Revoke Certificates',
                'description' => 'Can revoke certificates',
                'category' => 'certificates',
                'is_system_permission' => true,
            ],

            // Reporting and Analytics
            [
                'name' => 'view-reports',
                'display_name' => 'View Reports',
                'description' => 'Can view basic reports and analytics',
                'category' => 'reports',
                'is_system_permission' => true,
            ],
            [
                'name' => 'generate-reports',
                'display_name' => 'Generate Reports',
                'description' => 'Can generate custom reports and analytics',
                'category' => 'reports',
                'is_system_permission' => true,
            ],
            [
                'name' => 'export-data',
                'display_name' => 'Export Data',
                'description' => 'Can export data in various formats',
                'category' => 'reports',
                'is_system_permission' => true,
            ],

            // System Administration
            [
                'name' => 'system-admin',
                'display_name' => 'System Administration',
                'description' => 'Full system administration access',
                'category' => 'system',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-users',
                'display_name' => 'Manage Users',
                'description' => 'Can manage user accounts and access',
                'category' => 'system',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-roles',
                'display_name' => 'Manage Roles',
                'description' => 'Can manage roles and permissions',
                'category' => 'system',
                'is_system_permission' => true,
            ],
            [
                'name' => 'view-audit-logs',
                'display_name' => 'View Audit Logs',
                'description' => 'Can view system audit logs',
                'category' => 'system',
                'is_system_permission' => true,
            ],
            [
                'name' => 'manage-settings',
                'display_name' => 'Manage Settings',
                'description' => 'Can manage system settings and configuration',
                'category' => 'system',
                'is_system_permission' => true,
            ],

            // Communication
            [
                'name' => 'send-notifications',
                'display_name' => 'Send Notifications',
                'description' => 'Can send notifications to users',
                'category' => 'communication',
                'is_system_permission' => true,
            ],
            [
                'name' => 'send-bulk-emails',
                'display_name' => 'Send Bulk Emails',
                'description' => 'Can send bulk emails to members',
                'category' => 'communication',
                'is_system_permission' => true,
            ],

            // Special Permissions
            [
                'name' => 'view-all-institutions',
                'display_name' => 'View All Institutions',
                'description' => 'Can view data from all institutions (TRA only)',
                'category' => 'special',
                'is_system_permission' => true,
            ],
            [
                'name' => 'impersonate-users',
                'display_name' => 'Impersonate Users',
                'description' => 'Can login as other users for support purposes',
                'category' => 'special',
                'is_system_permission' => true,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}