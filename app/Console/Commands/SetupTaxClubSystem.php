<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class SetupTaxClubSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax-club:setup {--fresh : Drop all tables and start fresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the Tax Club Management System with roles, permissions, and test data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Setting up Tax Club Management System...');

        if ($this->option('fresh')) {
            $this->info('🔄 Dropping all tables and starting fresh...');
            Artisan::call('migrate:fresh');
            $this->info('✅ Database refreshed');
        } else {
            $this->info('🔄 Running migrations...');
            Artisan::call('migrate');
            $this->info('✅ Migrations completed');
        }

        $this->info('🌱 Seeding permissions, roles, and test users...');
        Artisan::call('db:seed');
        $this->info('✅ Database seeded');

        $this->info('🔧 Setting up additional configurations...');
        $this->setupAdditionalConfigs();

        $this->displayTestUsers();

        $this->info('');
        $this->info('🎉 Tax Club Management System setup completed successfully!');
        $this->info('');
        $this->info('📱 You can now access the system at: ' . config('app.url'));
        $this->info('🔐 Use any of the test accounts listed above to login');
        $this->info('🔑 Default password for all test accounts: password123');
    }

    private function setupAdditionalConfigs(): void
    {
        // Clear application cache
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        $this->info('✅ Application cache cleared');
    }

    private function displayTestUsers(): void
    {
        $this->info('');
        $this->info('👥 Test Users Created:');
        $this->info('========================');

        // TRA Officers
        $this->info('');
        $this->warn('🏛️  TRA OFFICERS (Full System Access):');
        $traOfficers = User::whereHas('roles', function ($query) {
            $query->where('name', 'tra_officer');
        })->get();

        foreach ($traOfficers as $user) {
            $this->line("   📧 {$user->email} - {$user->name}");
        }

        // Leaders
        $this->info('');
        $this->warn('👨‍💼 CLUB LEADERS (Institution Management):');
        $leaders = User::whereHas('roles', function ($query) {
            $query->where('name', 'leader');
        })->get();

        foreach ($leaders as $user) {
            $institution = $user->member?->institution?->name ?? 'No Institution';
            $this->line("   📧 {$user->email} - {$user->name} ({$institution})");
        }

        // Supervisors
        $this->info('');
        $this->warn('👨‍🏫 SUPERVISORS (Academic Staff):');
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })->get();

        foreach ($supervisors as $user) {
            $institution = $user->member?->institution?->name ?? 'No Institution';
            $this->line("   📧 {$user->email} - {$user->name} ({$institution})");
        }

        // Students (show first 5)
        $this->info('');
        $this->warn('🎓 STUDENTS (Club Members) - First 5:');
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->take(5)->get();

        foreach ($students as $user) {
            $institution = $user->member?->institution?->name ?? 'No Institution';
            $this->line("   📧 {$user->email} - {$user->name} ({$institution})");
        }

        $totalStudents = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->count();
        
        if ($totalStudents > 5) {
            $this->line("   ... and " . ($totalStudents - 5) . " more students");
        }

        // Custom Roles
        $customRoles = ['regional_coordinator', 'event_coordinator', 'budget_reviewer'];
        $this->info('');
        $this->warn('⚙️  CUSTOM ROLES:');
        
        foreach ($customRoles as $roleName) {
            $users = User::whereHas('roles', function ($query) use ($roleName) {
                $query->where('name', $roleName);
            })->get();

            foreach ($users as $user) {
                $role = $user->roles->where('name', $roleName)->first();
                $this->line("   📧 {$user->email} - {$user->name} ({$role->display_name})");
            }
        }

        $this->info('');
        $this->info('📊 System Statistics:');
        $this->info('=====================');
        $this->line('🏛️  Institutions: ' . \App\Models\Institution::count());
        $this->line('👥 Total Users: ' . User::count());
        $this->line('📝 Total Members: ' . \App\Models\Member::count());
        $this->line('🎭 Roles: ' . Role::count());
        $this->line('🔐 Permissions: ' . \App\Models\Permission::count());
    }
}