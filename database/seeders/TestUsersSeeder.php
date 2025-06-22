<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Institution;
use App\Models\Member;
use Carbon\Carbon;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create test institutions
        $this->createTestInstitutions();
        
        // Create TRA Officers
        $this->createTraOfficers();
        
        // Create Leaders and Supervisors
        $this->createLeadersAndSupervisors();
        
        // Create Students
        $this->createStudents();
        
        // Create users with custom roles
        $this->createCustomRoleUsers();
    }

    private function createTestInstitutions(): void
    {
        $institutions = [
            [
                'name' => 'University of Dar es Salaam',
                'code' => 'UDSM',
                'type' => 'university',
                'description' => 'Premier university in Tanzania',
                'address' => 'University Road',
                'city' => 'Dar es Salaam',
                'region' => 'Dar es Salaam',
                'phone' => '+255222410500',
                'email' => 'info@udsm.ac.tz',
                'website' => 'https://www.udsm.ac.tz',
                'status' => 'active',
                'established_date' => '1970-01-01',
            ],
            [
                'name' => 'Sokoine University of Agriculture',
                'code' => 'SUA',
                'type' => 'university',
                'description' => 'Leading agricultural university',
                'address' => 'Chuo Kikuu Road',
                'city' => 'Morogoro',
                'region' => 'Morogoro',
                'phone' => '+255232604651',
                'email' => 'info@sua.ac.tz',
                'website' => 'https://www.sua.ac.tz',
                'status' => 'active',
                'established_date' => '1984-01-01',
            ],
            [
                'name' => 'Mzumbe University',
                'code' => 'MZUMBE',
                'type' => 'university',
                'description' => 'Public administration and management university',
                'address' => 'Mzumbe',
                'city' => 'Morogoro',
                'region' => 'Morogoro',
                'phone' => '+255232604380',
                'email' => 'info@mzumbe.ac.tz',
                'website' => 'https://www.mzumbe.ac.tz',
                'status' => 'active',
                'established_date' => '1972-01-01',
            ],
            [
                'name' => 'Institute of Finance Management',
                'code' => 'IFM',
                'type' => 'institute',
                'description' => 'Specialized in finance and management education',
                'address' => 'Shaaban Robert Street',
                'city' => 'Dar es Salaam',
                'region' => 'Dar es Salaam',
                'phone' => '+255222112931',
                'email' => 'info@ifm.ac.tz',
                'website' => 'https://www.ifm.ac.tz',
                'status' => 'active',
                'established_date' => '1972-01-01',
            ],
            [
                'name' => 'Pending University College',
                'code' => 'PUC',
                'type' => 'college',
                'description' => 'New institution awaiting approval',
                'address' => '123 Education Street',
                'city' => 'Dodoma',
                'region' => 'Dodoma',
                'phone' => '+255261234567',
                'email' => 'info@puc.ac.tz',
                'website' => 'https://www.puc.ac.tz',
                'status' => 'pending',
                'established_date' => '2025-01-01',
            ],
        ];

        foreach ($institutions as $institutionData) {
            Institution::updateOrCreate(
                ['code' => $institutionData['code']],
                $institutionData
            );
        }
    }

    private function createTraOfficers(): void
    {
        $traRole = Role::where('name', 'tra_officer')->first();
        
        $traOfficers = [
            [
                'name' => 'Dr. Amina Hassan',
                'email' => 'amina.hassan@tra.go.tz',
                'phone' => '+255765123456',
                'national_id' => '19800515123456789',
                'date_of_birth' => '1980-05-15',
                'gender' => 'female',
                'status' => 'active',
                'bio' => 'Senior TRA Officer with 15 years experience in tax education and policy development.',
            ],
            [
                'name' => 'Mr. John Mwalimu',
                'email' => 'john.mwalimu@tra.go.tz',
                'phone' => '+255754987654',
                'national_id' => '19750312987654321',
                'date_of_birth' => '1975-03-12',
                'gender' => 'male',
                'status' => 'active',
                'bio' => 'TRA Officer specializing in institutional partnerships and tax club oversight.',
            ],
            [
                'name' => 'Ms. Grace Kileo',
                'email' => 'grace.kileo@tra.go.tz',
                'phone' => '+255713456789',
                'national_id' => '19850920456789123',
                'date_of_birth' => '1985-09-20',
                'gender' => 'female',
                'status' => 'active',
                'bio' => 'Budget review specialist and compliance officer for educational institutions.',
            ],
        ];

        foreach ($traOfficers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'last_login_at' => now()->subDays(rand(1, 7)),
                ])
            );

            $user->assignRole('tra_officer');
        }
    }

    private function createLeadersAndSupervisors(): void
    {
        $institutions = Institution::where('status', 'active')->get();
        $leaderRole = Role::where('name', 'leader')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();

        $leadersData = [
            [
                'name' => 'Prof. Mary Ndumbaro',
                'email' => 'mary.ndumbaro@udsm.ac.tz',
                'phone' => '+255788123456',
                'role_type' => 'supervisor',
                'institution_code' => 'UDSM',
                'course_of_study' => 'Faculty of Law',
                'member_type' => 'supervisor',
                'bio' => 'Professor of Tax Law with extensive experience in tax education.',
            ],
            [
                'name' => 'Mr. Peter Mwangoka',
                'email' => 'peter.mwangoka@udsm.ac.tz',
                'phone' => '+255799234567',
                'role_type' => 'leader',
                'institution_code' => 'UDSM',
                'course_of_study' => 'Bachelor of Laws (LLB)',
                'year_of_study' => 3,
                'member_type' => 'leader',
                'bio' => 'Final year law student passionate about tax education and advocacy.',
            ],
            [
                'name' => 'Dr. Agnes Mwakifuna',
                'email' => 'agnes.mwakifuna@sua.ac.tz',
                'phone' => '+255756345678',
                'role_type' => 'supervisor',
                'institution_code' => 'SUA',
                'course_of_study' => 'Department of Agricultural Economics',
                'member_type' => 'supervisor',
                'bio' => 'Agricultural economist with focus on rural taxation and development.',
            ],
            [
                'name' => 'Ms. Sarah Kilonzo',
                'email' => 'sarah.kilonzo@sua.ac.tz',
                'phone' => '+255767456789',
                'role_type' => 'leader',
                'institution_code' => 'SUA',
                'course_of_study' => 'Bachelor of Agricultural Economics',
                'year_of_study' => 4,
                'member_type' => 'leader',
                'bio' => 'Agricultural economics student interested in rural tax policy.',
            ],
            [
                'name' => 'Prof. Hassan Mfinanga',
                'email' => 'hassan.mfinanga@mzumbe.ac.tz',
                'phone' => '+255745567890',
                'role_type' => 'supervisor',
                'institution_code' => 'MZUMBE',
                'course_of_study' => 'Department of Public Administration',
                'member_type' => 'supervisor',
                'bio' => 'Public administration expert with focus on tax policy implementation.',
            ],
            [
                'name' => 'Mr. David Msigwa',
                'email' => 'david.msigwa@mzumbe.ac.tz',
                'phone' => '+255734678901',
                'role_type' => 'leader',
                'institution_code' => 'MZUMBE',
                'course_of_study' => 'Bachelor of Public Administration',
                'year_of_study' => 3,
                'member_type' => 'leader',
                'bio' => 'Public administration student with interest in tax administration.',
            ],
            [
                'name' => 'Dr. Rehema Shekilango',
                'email' => 'rehema.shekilango@ifm.ac.tz',
                'phone' => '+255723789012',
                'role_type' => 'supervisor',
                'institution_code' => 'IFM',
                'course_of_study' => 'Department of Accounting and Finance',
                'member_type' => 'supervisor',
                'bio' => 'Finance and accounting expert specializing in tax accounting.',
            ],
            [
                'name' => 'Ms. Elizabeth Mushi',
                'email' => 'elizabeth.mushi@ifm.ac.tz',
                'phone' => '+255712890123',
                'role_type' => 'leader',
                'institution_code' => 'IFM',
                'course_of_study' => 'Bachelor of Accounting and Finance',
                'year_of_study' => 4,
                'member_type' => 'leader',
                'bio' => 'Accounting and finance student passionate about tax compliance.',
            ],
        ];

        foreach ($leadersData as $userData) {
            $institution = Institution::where('code', $userData['institution_code'])->first();
            if (!$institution) continue;

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => $userData['phone'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'bio' => $userData['bio'],
                    'last_login_at' => now()->subDays(rand(1, 5)),
                ]
            );

            // Assign role
            $user->assignRole($userData['role_type']);

            // Create member record
            Member::updateOrCreate(
                ['user_id' => $user->id, 'institution_id' => $institution->id],
                [
                    'course_of_study' => $userData['course_of_study'],
                    'year_of_study' => $userData['year_of_study'] ?? null,
                    'member_type' => $userData['member_type'],
                    'status' => 'active',
                    'joined_date' => now()->subMonths(rand(6, 24)),
                    'interests' => ['tax_policy', 'compliance', 'education'],
                    'skills' => ['leadership', 'communication', 'tax_knowledge'],
                    'motivation' => 'Passionate about promoting tax compliance and education',
                    'approved_by' => 1, // Assuming first TRA officer
                    'approved_at' => now()->subMonths(rand(6, 24)),
                ]
            );
        }
    }

    private function createStudents(): void
    {
        $institutions = Institution::where('status', 'active')->get();
        $studentRole = Role::where('name', 'student')->first();

        $studentsData = [
            // UDSM Students
            [
                'name' => 'Alice Mwamba',
                'email' => 'alice.mwamba@student.udsm.ac.tz',
                'institution_code' => 'UDSM',
                'course_of_study' => 'Bachelor of Laws (LLB)',
                'year_of_study' => 2,
            ],
            [
                'name' => 'Michael Temba',
                'email' => 'michael.temba@student.udsm.ac.tz',
                'institution_code' => 'UDSM',
                'course_of_study' => 'Bachelor of Commerce',
                'year_of_study' => 1,
            ],
            [
                'name' => 'Fatma Ally',
                'email' => 'fatma.ally@student.udsm.ac.tz',
                'institution_code' => 'UDSM',
                'course_of_study' => 'Bachelor of Economics',
                'year_of_study' => 3,
            ],

            // SUA Students
            [
                'name' => 'Joseph Lyimo',
                'email' => 'joseph.lyimo@student.sua.ac.tz',
                'institution_code' => 'SUA',
                'course_of_study' => 'Bachelor of Agricultural Economics',
                'year_of_study' => 2,
            ],
            [
                'name' => 'Rose Mwakasege',
                'email' => 'rose.mwakasege@student.sua.ac.tz',
                'institution_code' => 'SUA',
                'course_of_study' => 'Bachelor of Agribusiness',
                'year_of_study' => 1,
            ],

            // Mzumbe Students
            [
                'name' => 'Frank Massawe',
                'email' => 'frank.massawe@student.mzumbe.ac.tz',
                'institution_code' => 'MZUMBE',
                'course_of_study' => 'Bachelor of Public Administration',
                'year_of_study' => 2,
            ],
            [
                'name' => 'Grace Mwalimu',
                'email' => 'grace.mwalimu@student.mzumbe.ac.tz',
                'institution_code' => 'MZUMBE',
                'course_of_study' => 'Bachelor of Human Resource Management',
                'year_of_study' => 3,
            ],

            // IFM Students
            [
                'name' => 'Emmanuel Shayo',
                'email' => 'emmanuel.shayo@student.ifm.ac.tz',
                'institution_code' => 'IFM',
                'course_of_study' => 'Bachelor of Accounting and Finance',
                'year_of_study' => 2,
            ],
            [
                'name' => 'Neema Kamwenda',
                'email' => 'neema.kamwenda@student.ifm.ac.tz',
                'institution_code' => 'IFM',
                'course_of_study' => 'Bachelor of Banking and Finance',
                'year_of_study' => 1,
            ],
        ];

        foreach ($studentsData as $userData) {
            $institution = Institution::where('code', $userData['institution_code'])->first();
            if (!$institution) continue;

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'phone' => '+25575' . rand(1000000, 9999999),
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'date_of_birth' => now()->subYears(rand(19, 25)),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'last_login_at' => now()->subDays(rand(1, 3)),
                ]
            );

            // Assign student role
            $user->assignRole('student');

            // Create member record
            Member::updateOrCreate(
                ['user_id' => $user->id, 'institution_id' => $institution->id],
                [
                    'student_id' => strtoupper($userData['institution_code']) . '/' . rand(2020, 2025) . '/' . rand(100, 999),
                    'course_of_study' => $userData['course_of_study'],
                    'year_of_study' => $userData['year_of_study'],
                    'member_type' => 'student',
                    'status' => 'active',
                    'joined_date' => now()->subMonths(rand(3, 18)),
                    'interests' => ['tax_compliance', 'entrepreneurship', 'financial_literacy'],
                    'skills' => ['research', 'presentation', 'teamwork'],
                    'motivation' => 'Want to learn about tax obligations for future business ventures',
                    'approved_by' => 1,
                    'approved_at' => now()->subMonths(rand(3, 18)),
                ]
            );
        }

        // Create some pending students
        $pendingStudents = [
            [
                'name' => 'Pending Student One',
                'email' => 'pending1@student.udsm.ac.tz',
                'institution_code' => 'UDSM',
                'course_of_study' => 'Bachelor of Laws (LLB)',
                'year_of_study' => 1,
            ],
            [
                'name' => 'Pending Student Two',
                'email' => 'pending2@student.sua.ac.tz',
                'institution_code' => 'SUA',
                'course_of_study' => 'Bachelor of Agricultural Economics',
                'year_of_study' => 1,
            ],
        ];

        foreach ($pendingStudents as $userData) {
            $institution = Institution::where('code', $userData['institution_code'])->first();
            if (!$institution) continue;

            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => '+25575' . rand(1000000, 9999999),
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'status' => 'pending',
                'date_of_birth' => now()->subYears(rand(18, 22)),
                'gender' => rand(0, 1) ? 'male' : 'female',
            ]);

            $user->assignRole('student');

            Member::create([
                'user_id' => $user->id,
                'institution_id' => $institution->id,
                'student_id' => strtoupper($userData['institution_code']) . '/' . date('Y') . '/' . rand(100, 999),
                'course_of_study' => $userData['course_of_study'],
                'year_of_study' => $userData['year_of_study'],
                'member_type' => 'student',
                'status' => 'pending',
                'joined_date' => now(),
                'interests' => ['tax_education', 'compliance'],
                'skills' => ['eager_to_learn'],
                'motivation' => 'Want to join the tax club to learn about taxation',
            ]);
        }
    }

    private function createCustomRoleUsers(): void
    {
        // Regional Coordinator
        $user = User::create([
            'name' => 'Regional Coordinator Dar',
            'email' => 'coordinator.dar@tra.go.tz',
            'phone' => '+255701234567',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => 'active',
            'bio' => 'Coordinates tax club activities in Dar es Salaam region',
            'last_login_at' => now()->subDays(2),
        ]);
        $user->assignRole('regional_coordinator');

        // Event Coordinator
        $user = User::create([
            'name' => 'Event Coordinator National',
            'email' => 'events.coordinator@tra.go.tz',
            'phone' => '+255702345678',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => 'active',
            'bio' => 'Manages national tax club events and workshops',
            'last_login_at' => now()->subDays(1),
        ]);
        $user->assignRole('event_coordinator');

        // Budget Reviewer
        $user = User::create([
            'name' => 'Budget Review Specialist',
            'email' => 'budget.reviewer@tra.go.tz',
            'phone' => '+255703456789',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'status' => 'active',
            'bio' => 'Reviews and analyzes budget proposals from institutions',
            'last_login_at' => now()->subHours(6),
        ]);
        $user->assignRole('budget_reviewer');
    }
}