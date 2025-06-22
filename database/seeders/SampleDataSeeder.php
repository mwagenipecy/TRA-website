<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\Activity;
use App\Models\EventRegistration;
use App\Models\Institution;
use App\Models\User;
use App\Models\Member;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createSampleEvents();
        $this->createSampleBudgets();
        $this->createSampleActivities();
        $this->createEventRegistrations();
    }

    private function createSampleEvents(): void
    {
        $institutions = Institution::where('status', 'active')->get();
        $leaders = User::whereHas('roles', function ($query) {
            $query->where('name', 'leader');
        })->get();

        $events = [
            [
                'title' => 'Tax Compliance Workshop for Students',
                'description' => 'An interactive workshop designed to educate students about tax compliance requirements and best practices for future entrepreneurs.',
                'type' => 'workshop',
                'venue' => 'Main Auditorium',
                'address' => 'University Campus, Dar es Salaam',
                'start_date' => now()->addDays(7)->setTime(9, 0),
                'end_date' => now()->addDays(7)->setTime(16, 0),
                'max_participants' => 150,
                'is_free' => true,
                'requires_approval' => true,
                'allow_non_members' => false,
                'registration_start' => now(),
                'registration_end' => now()->addDays(5),
                'status' => 'published',
                'objectives' => [
                    'Understand basic tax obligations for individuals',
                    'Learn about VAT requirements for businesses',
                    'Explore digital tax filing systems',
                ],
                'target_audience' => ['Final year students', 'Business students', 'Entrepreneurship enthusiasts'],
                'requirements' => ['Student ID', 'Notebook and pen', 'Basic calculator'],
                'tags' => ['tax_compliance', 'workshop', 'students', 'education'],
            ],
            [
                'title' => 'Digital Tax Filing Seminar',
                'description' => 'Learn how to use the TRA online portal for tax filing and compliance monitoring.',
                'type' => 'seminar',
                'venue' => 'Computer Lab 1',
                'address' => 'IT Building, Floor 2',
                'start_date' => now()->addDays(14)->setTime(14, 0),
                'end_date' => now()->addDays(14)->setTime(17, 0),
                'max_participants' => 40,
                'is_free' => true,
                'requires_approval' => false,
                'allow_non_members' => true,
                'registration_start' => now(),
                'registration_end' => now()->addDays(12),
                'status' => 'published',
                'objectives' => [
                    'Navigate the TRA online portal',
                    'Submit tax returns electronically',
                    'Track payment status online',
                ],
                'target_audience' => ['All students', 'Faculty members', 'Administrative staff'],
                'requirements' => ['Laptop or tablet', 'Internet connection', 'Email address'],
                'tags' => ['digital_filing', 'seminar', 'technology', 'tra_portal'],
            ],
            [
                'title' => 'VAT for Small Businesses Training',
                'description' => 'Comprehensive training on VAT registration, calculation, and filing requirements for small business owners.',
                'type' => 'training',
                'venue' => 'Business Incubation Center',
                'address' => 'Innovation Hub, University Road',
                'start_date' => now()->addDays(21)->setTime(8, 0),
                'end_date' => now()->addDays(21)->setTime(17, 0),
                'max_participants' => 60,
                'is_free' => false,
                'registration_fee' => 25000.00,
                'requires_approval' => true,
                'allow_non_members' => true,
                'registration_start' => now(),
                'registration_end' => now()->addDays(18),
                'status' => 'published',
                'objectives' => [
                    'Understand VAT registration thresholds',
                    'Learn VAT calculation methods',
                    'Master VAT return filing procedures',
                ],
                'target_audience' => ['Small business owners', 'Entrepreneurs', 'Business students'],
                'requirements' => ['Business registration documents', 'Calculator', 'Lunch will be provided'],
                'tags' => ['vat', 'training', 'small_business', 'entrepreneurs'],
            ],
            [
                'title' => 'Tax Policy and Economic Development Conference',
                'description' => 'Annual conference bringing together tax experts, policymakers, and students to discuss the role of taxation in economic development.',
                'type' => 'conference',
                'venue' => 'Julius Nyerere Convention Centre',
                'address' => 'JNCC, Dar es Salaam',
                'start_date' => now()->addDays(45)->setTime(8, 0),
                'end_date' => now()->addDays(47)->setTime(17, 0),
                'max_participants' => 500,
                'is_free' => false,
                'registration_fee' => 150000.00,
                'requires_approval' => true,
                'allow_non_members' => true,
                'registration_start' => now(),
                'registration_end' => now()->addDays(40),
                'status' => 'published',
                'objectives' => [
                    'Explore latest tax policy developments',
                    'Network with tax professionals',
                    'Learn from international best practices',
                ],
                'target_audience' => ['Tax professionals', 'Policy makers', 'Graduate students', 'Researchers'],
                'requirements' => ['Conference materials included', 'Business attire required', 'Networking sessions'],
                'tags' => ['conference', 'tax_policy', 'economic_development', 'networking'],
            ],
            [
                'title' => 'Student Tax Quiz Competition',
                'description' => 'Inter-university tax knowledge competition for students with prizes and certificates.',
                'type' => 'competition',
                'venue' => 'National Stadium - Conference Hall',
                'address' => 'Kinondoni, Dar es Salaam',
                'start_date' => now()->addDays(30)->setTime(9, 0),
                'end_date' => now()->addDays(30)->setTime(15, 0),
                'max_participants' => 200,
                'is_free' => true,
                'requires_approval' => true,
                'allow_non_members' => false,
                'registration_start' => now(),
                'registration_end' => now()->addDays(25),
                'status' => 'published',
                'objectives' => [
                    'Test tax knowledge among students',
                    'Promote healthy competition',
                    'Recognize top performers',
                ],
                'target_audience' => ['University students', 'Tax club members'],
                'requirements' => ['Student ID', 'University tax club membership', 'Team of 3-4 members'],
                'tags' => ['competition', 'quiz', 'students', 'inter_university'],
            ],
        ];

        foreach ($events as $index => $eventData) {
            $institution = $institutions->random();
            $creator = $leaders->where('member.institution_id', $institution->id)->first() ?? $leaders->first();

            Event::create(array_merge($eventData, [
                'institution_id' => $institution->id,
                'created_by' => $creator->id,
            ]));
        }

        // Create some past events
        $pastEvents = [
            [
                'title' => 'Introduction to Taxation - Completed Workshop',
                'description' => 'Basic taxation principles workshop that was conducted last month.',
                'type' => 'workshop',
                'venue' => 'Lecture Hall A',
                'start_date' => now()->subDays(30)->setTime(9, 0),
                'end_date' => now()->subDays(30)->setTime(16, 0),
                'status' => 'completed',
                'max_participants' => 100,
                'is_free' => true,
            ],
            [
                'title' => 'Tax History and Evolution - Past Seminar',
                'description' => 'Educational seminar on the evolution of tax systems in Tanzania.',
                'type' => 'seminar',
                'venue' => 'Library Conference Room',
                'start_date' => now()->subDays(45)->setTime(14, 0),
                'end_date' => now()->subDays(45)->setTime(17, 0),
                'status' => 'completed',
                'max_participants' => 50,
                'is_free' => true,
            ],
        ];

        foreach ($pastEvents as $eventData) {
            $institution = $institutions->random();
            $creator = $leaders->where('member.institution_id', $institution->id)->first() ?? $leaders->first();

            Event::create(array_merge($eventData, [
                'institution_id' => $institution->id,
                'created_by' => $creator->id,
                'objectives' => ['Educational objective', 'Knowledge sharing'],
                'target_audience' => ['Students', 'Faculty'],
                'requirements' => ['Basic requirements'],
                'tags' => ['education', 'past_event'],
            ]));
        }
    }

    private function createSampleBudgets(): void
    {
        $institutions = Institution::where('status', 'active')->get();
        $leaders = User::whereHas('roles', function ($query) {
            $query->where('name', 'leader');
        })->get();

        $budgets = [
            [
                'title' => 'Annual Tax Club Operations 2025',
                'type' => 'yearly',
                'description' => 'Annual budget for tax club operations including events, materials, and administrative costs.',
                'total_amount' => 5000000.00,
                'financial_year' => 2025,
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'status' => 'approved',
                'priority_level' => 4,
                'objectives' => [
                    'Support annual tax education activities',
                    'Provide learning materials for members',
                    'Organize quarterly workshops and seminars',
                ],
                'approved_amount' => 4500000.00,
                'spent_amount' => 1250000.00,
                'items' => [
                    [
                        'category' => 'events',
                        'item_name' => 'Workshop Materials',
                        'description' => 'Printing and stationery for workshops',
                        'quantity' => 4,
                        'unit_cost' => 150000.00,
                        'unit_of_measure' => 'per workshop',
                        'justification' => 'Essential for hands-on learning activities',
                        'is_approved' => true,
                        'priority' => 4,
                        'is_mandatory' => true,
                    ],
                    [
                        'category' => 'venue',
                        'item_name' => 'Venue Rental',
                        'description' => 'Conference hall rental for major events',
                        'quantity' => 6,
                        'unit_cost' => 200000.00,
                        'unit_of_measure' => 'per day',
                        'justification' => 'Required for large capacity events',
                        'is_approved' => true,
                        'priority' => 5,
                        'is_mandatory' => true,
                    ],
                    [
                        'category' => 'catering',
                        'item_name' => 'Refreshments',
                        'description' => 'Tea breaks and lunch for participants',
                        'quantity' => 800,
                        'unit_cost' => 3500.00,
                        'unit_of_measure' => 'per person',
                        'justification' => 'Enhance participant experience and networking',
                        'is_approved' => true,
                        'priority' => 3,
                        'is_mandatory' => false,
                    ],
                ],
            ],
            [
                'title' => 'Tax Compliance Workshop - March 2025',
                'type' => 'event',
                'description' => 'Budget for the upcoming tax compliance workshop for students.',
                'total_amount' => 850000.00,
                'financial_year' => 2025,
                'start_date' => '2025-03-01',
                'end_date' => '2025-03-31',
                'status' => 'submitted',
                'priority_level' => 3,
                'objectives' => [
                    'Educate 150 students on tax compliance',
                    'Provide practical training materials',
                    'Issue certificates to participants',
                ],
                'items' => [
                    [
                        'category' => 'materials',
                        'item_name' => 'Training Booklets',
                        'description' => 'Printed tax compliance guides',
                        'quantity' => 150,
                        'unit_cost' => 2000.00,
                        'unit_of_measure' => 'per copy',
                        'justification' => 'Reference material for participants',
                        'priority' => 4,
                        'is_mandatory' => true,
                    ],
                    [
                        'category' => 'equipment',
                        'item_name' => 'Projector Rental',
                        'description' => 'Audio-visual equipment for presentations',
                        'quantity' => 1,
                        'unit_cost' => 100000.00,
                        'unit_of_measure' => 'per day',
                        'justification' => 'Essential for presentations and demonstrations',
                        'priority' => 5,
                        'is_mandatory' => true,
                    ],
                ],
            ],
            [
                'title' => 'Digital Equipment Upgrade 2025',
                'type' => 'equipment',
                'description' => 'Purchase of laptops and projectors for tax club activities.',
                'total_amount' => 3200000.00,
                'financial_year' => 2025,
                'start_date' => '2025-02-01',
                'end_date' => '2025-04-30',
                'status' => 'under_review',
                'priority_level' => 4,
                'objectives' => [
                    'Modernize tax club equipment',
                    'Support digital tax filing training',
                    'Enhance presentation capabilities',
                ],
                'items' => [
                    [
                        'category' => 'equipment',
                        'item_name' => 'Laptops',
                        'description' => 'Laptops for tax software demonstrations',
                        'quantity' => 4,
                        'unit_cost' => 650000.00,
                        'unit_of_measure' => 'per unit',
                        'justification' => 'Required for hands-on digital tax filing training',
                        'priority' => 5,
                        'is_mandatory' => true,
                    ],
                    [
                        'category' => 'equipment',
                        'item_name' => 'Projectors',
                        'description' => 'Modern projectors for presentations',
                        'quantity' => 2,
                        'unit_cost' => 450000.00,
                        'unit_of_measure' => 'per unit',
                        'justification' => 'Improve presentation quality and visibility',
                        'priority' => 4,
                        'is_mandatory' => false,
                    ],
                ],
            ],
        ];

        foreach ($budgets as $budgetData) {
            $institution = $institutions->random();
            $creator = $leaders->where('member.institution_id', $institution->id)->first() ?? $leaders->first();
            
            $items = $budgetData['items'];
            unset($budgetData['items']);

            $budget = Budget::create(array_merge($budgetData, [
                'institution_id' => $institution->id,
                'created_by' => $creator->id,
            ]));

            // Set remaining amount for approved budgets
            if ($budget->status === 'approved') {
                $budget->update([
                    'remaining_amount' => $budget->approved_amount - $budget->spent_amount,
                    'approved_by' => User::whereHas('roles', function ($query) {
                        $query->where('name', 'tra_officer');
                    })->first()->id,
                    'approved_at' => now()->subDays(rand(10, 60)),
                ]);
            }

            // Create budget items
            foreach ($items as $itemData) {
                BudgetItem::create(array_merge($itemData, [
                    'budget_id' => $budget->id,
                ]));
            }
        }
    }

    private function createSampleActivities(): void
    {
        $users = User::all();
        $institutions = Institution::all();
        $events = Event::all();
        $budgets = Budget::all();
        $members = Member::all();

        $activities = [];

        // Generate activities for the last 30 days
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $institution = $institutions->random();
            $performedAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23));

            $activityTypes = [
                'user_registered' => [
                    'description' => "{$user->name} registered for tax club membership",
                    'subject' => $members->random(),
                ],
                'event_created' => [
                    'description' => "New event '{$events->random()->title}' was created",
                    'subject' => $events->random(),
                ],
                'event_registered' => [
                    'description' => "{$user->name} registered for an upcoming event",
                    'subject' => $events->random(),
                ],
                'budget_submitted' => [
                    'description' => "Budget proposal '{$budgets->random()->title}' was submitted for review",
                    'subject' => $budgets->random(),
                ],
                'budget_approved' => [
                    'description' => "Budget '{$budgets->random()->title}' was approved by TRA",
                    'subject' => $budgets->random(),
                ],
                'member_approved' => [
                    'description' => "Member application for {$user->name} was approved",
                    'subject' => $members->random(),
                ],
                'institution_approved' => [
                    'description' => "Institution '{$institution->name}' was approved for tax club program",
                    'subject' => $institution,
                ],
            ];

            $type = array_rand($activityTypes);
            $activityData = $activityTypes[$type];

            $activities[] = [
                'type' => $type,
                'description' => $activityData['description'],
                'user_id' => $user->id,
                'institution_id' => $institution->id,
                'subject_type' => get_class($activityData['subject']),
                'subject_id' => $activityData['subject']->id,
                'properties' =>  json_encode( [
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Sample Activity)',
                ]),
                'performed_at' => $performedAt,
                'created_at' => $performedAt,
                'updated_at' => $performedAt,
            ];
        }

        Activity::insert($activities);
    }

    private function createEventRegistrations(): void
    {
        $events = Event::where('status', 'published')->get();
        $students = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->get();

        foreach ($events as $event) {
            // Register random number of students for each event
            $registrationCount = rand(5, min(30, $event->max_participants ?? 100));
            $selectedStudents = $students->random($registrationCount);

            foreach ($selectedStudents as $student) {
                $registrationDate = $event->registration_start ?? $event->created_at;
                $status = rand(1, 10) > 2 ? 'approved' : 'pending'; // 80% approval rate

                EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $student->id,
                    'is_member' => $student->member ? true : false,
                    'status' => $status,
                    'registered_at' => $registrationDate->addHours(rand(1, 48)),
                    'approved_at' => $status === 'approved' ? $registrationDate->addDays(rand(1, 3)) : null,
                    'approved_by' => $status === 'approved' ? 
                        User::whereHas('roles', function ($query) {
                            $query->whereIn('name', ['leader', 'supervisor']);
                        })->inRandomOrder()->first()->id : null,
                    'additional_info' => [
                        'dietary_restrictions' => rand(1, 10) > 8 ? 'Vegetarian' : null,
                        'special_needs' => rand(1, 20) > 18 ? 'Wheelchair access' : null,
                    ],
                    'payment_required' => !$event->is_free,
                    'payment_status' => !$event->is_free ? (rand(1, 10) > 3 ? 'paid' : 'pending') : null,
                    'amount_paid' => !$event->is_free && rand(1, 10) > 3 ? $event->registration_fee : null,
                    'attended' => $event->isPast() ? (rand(1, 10) > 2) : false, // 80% attendance for past events
                    'check_in_time' => $event->isPast() && rand(1, 10) > 2 ? 
                        $event->start_date->addMinutes(rand(-15, 30)) : null,
                    'rating' => $event->isPast() && rand(1, 10) > 5 ? rand(3, 5) : null,
                    'feedback' => $event->isPast() && rand(1, 10) > 7 ? 
                        'Great event! Learned a lot about tax compliance.' : null,
                ]);
            }
        }
    }
}