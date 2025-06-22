<?php

// database/seeders/NotificationSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Budget;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Member;
use App\Models\Institution;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('status', 'active')->get();
        $budgets = Budget::with('institution')->get();
        $certificates = Certificate::with(['user', 'institution'])->get();
        $events = Event::with('institution')->get();
        $members = Member::with(['user', 'institution'])->get();
        $institutions = Institution::where('status', 'active')->get();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $this->command->info('Creating notification dump data...');

        foreach ($users as $user) {
            $this->createBudgetNotifications($user, $budgets);
            $this->createCertificateNotifications($user, $certificates);
            $this->createEventNotifications($user, $events);
            $this->createMembershipNotifications($user, $members);
            $this->createSystemNotifications($user);
            $this->createGeneralNotifications($user);
        }

        $this->command->info('Notification dump data created successfully!');
    }

    /**
     * Create budget-related notifications
     */
    private function createBudgetNotifications($user, $budgets)
    {
        $budgetNotifications = [
            // Budget Approved
            [
                'type' => 'budget',
                'title' => 'Budget Approved',
                'message' => 'Your annual budget request has been approved by TRA.',
                'action_url' => '/budgets/1',
                'details' => [
                    'budget_code' => 'UDSM-2024-ANN-001',
                    'approved_amount' => 'TZS 15,000,000',
                    'approved_by' => 'Dr. John Mwalimu',
                    'approval_date' => Carbon::now()->subDays(2)->format('M d, Y')
                ]
            ],
            // Budget Rejected
            [
                'type' => 'budget',
                'title' => 'Budget Rejected',
                'message' => 'Your event budget request has been rejected. Please review and resubmit.',
                'action_url' => '/budgets/2',
                'details' => [
                    'budget_code' => 'UDSM-2024-EVT-002',
                    'rejected_by' => 'TRA Finance Team',
                    'rejection_reason' => 'Insufficient documentation provided',
                    'rejection_date' => Carbon::now()->subDays(5)->format('M d, Y')
                ]
            ],
            // Budget Revision Required
            [
                'type' => 'budget',
                'title' => 'Budget Revision Required',
                'message' => 'Please revise your budget proposal based on review comments.',
                'action_url' => '/budgets/3',
                'details' => [
                    'budget_code' => 'UDSM-2024-PRJ-003',
                    'requested_amount' => 'TZS 8,500,000',
                    'reviewer' => 'Ms. Sarah Kimaro',
                    'review_date' => Carbon::now()->subDays(1)->format('M d, Y')
                ]
            ],
            // Budget Submitted
            [
                'type' => 'budget',
                'title' => 'Budget Submitted for Review',
                'message' => 'Your budget has been successfully submitted for TRA review.',
                'action_url' => '/budgets/4',
                'details' => [
                    'budget_code' => 'UDSM-2024-EQP-004',
                    'submitted_amount' => 'TZS 12,000,000',
                    'submission_date' => Carbon::now()->subHours(6)->format('M d, Y H:i'),
                    'expected_review_time' => '5-7 business days'
                ]
            ],
            // Budget Expenditure Alert
            [
                'type' => 'budget',
                'title' => 'Budget Expenditure Alert',
                'message' => 'You have used 80% of your approved budget. Monitor spending carefully.',
                'action_url' => '/budgets/5',
                'details' => [
                    'budget_code' => 'UDSM-2024-ANN-005',
                    'approved_amount' => 'TZS 10,000,000',
                    'spent_amount' => 'TZS 8,000,000',
                    'remaining_amount' => 'TZS 2,000,000',
                    'utilization_rate' => '80%'
                ]
            ]
        ];

        foreach ($budgetNotifications as $index => $notification) {
            $this->createNotification($user, $notification, Carbon::now()->subDays(rand(0, 30)));
        }
    }

    /**
     * Create certificate-related notifications
     */
    private function createCertificateNotifications($user, $certificates)
    {
        $certificateNotifications = [
            // Certificate Issued
            [
                'type' => 'certificate',
                'title' => 'New Certificate Issued',
                'message' => 'Congratulations! You have been awarded a certificate of completion.',
                'action_url' => '/certificates/1',
                'details' => [
                    'certificate_title' => 'Web Development Bootcamp Completion',
                    'certificate_code' => 'UDSM-2024-COM-001',
                    'certificate_type' => 'Completion',
                    'issue_date' => Carbon::now()->subDays(3)->format('M d, Y'),
                    'institution' => 'University of Dar es Salaam'
                ]
            ],
            // Certificate Revoked
            [
                'type' => 'certificate',
                'title' => 'Certificate Revoked',
                'message' => 'Your certificate has been revoked due to policy violations.',
                'action_url' => '/certificates/2',
                'details' => [
                    'certificate_title' => 'Digital Marketing Certificate',
                    'certificate_code' => 'UDSM-2024-COM-002',
                    'revocation_date' => Carbon::now()->subDays(7)->format('M d, Y'),
                    'revocation_reason' => 'False information provided during verification'
                ]
            ],
            // Certificate Expiring Soon
            [
                'type' => 'certificate',
                'title' => 'Certificate Expiring Soon',
                'message' => 'Your certificate will expire in 30 days. Consider renewal if applicable.',
                'action_url' => '/certificates/3',
                'details' => [
                    'certificate_title' => 'Project Management Professional',
                    'certificate_code' => 'UDSM-2023-ACH-015',
                    'expiry_date' => Carbon::now()->addDays(30)->format('M d, Y'),
                    'renewal_available' => 'Yes'
                ]
            ],
            // Certificate Verification Request
            [
                'type' => 'certificate',
                'title' => 'Certificate Verification Request',
                'message' => 'Someone has requested verification of your certificate.',
                'action_url' => '/certificates/4',
                'details' => [
                    'certificate_code' => 'UDSM-2024-COM-001',
                    'verification_date' => Carbon::now()->subHours(2)->format('M d, Y H:i'),
                    'verifier_location' => 'Dar es Salaam, Tanzania',
                    'verification_status' => 'Successful'
                ]
            ]
        ];

        foreach ($certificateNotifications as $notification) {
            $this->createNotification($user, $notification, Carbon::now()->subDays(rand(0, 15)));
        }
    }

    /**
     * Create event-related notifications
     */
    private function createEventNotifications($user, $events)
    {
        $eventNotifications = [
            // Event Registration Approved
            [
                'type' => 'event',
                'title' => 'Event Registration Approved',
                'message' => 'Your registration for the Annual Tech Conference has been approved.',
                'action_url' => '/events/1',
                'details' => [
                    'event_title' => 'Annual Tech Conference 2024',
                    'event_date' => Carbon::now()->addDays(15)->format('M d, Y'),
                    'event_location' => 'UDSM Main Campus',
                    'registration_fee' => 'TZS 50,000',
                    'approval_date' => Carbon::now()->subDays(2)->format('M d, Y')
                ]
            ],
            // Event Registration Rejected
            [
                'type' => 'event',
                'title' => 'Event Registration Rejected',
                'message' => 'Your registration was rejected due to incomplete requirements.',
                'action_url' => '/events/2',
                'details' => [
                    'event_title' => 'Leadership Workshop',
                    'rejection_reason' => 'Missing required documentation',
                    'rejection_date' => Carbon::now()->subDays(4)->format('M d, Y'),
                    'resubmission_allowed' => 'Yes'
                ]
            ],
            // Event Reminder
            [
                'type' => 'event',
                'title' => 'Event Reminder',
                'message' => 'Reminder: Digital Marketing Workshop starts tomorrow at 9:00 AM.',
                'action_url' => '/events/3',
                'details' => [
                    'event_title' => 'Digital Marketing Workshop',
                    'event_date' => Carbon::now()->addDay()->format('M d, Y'),
                    'event_time' => '09:00 AM - 05:00 PM',
                    'venue' => 'Computer Science Building, Room 101',
                    'bring_items' => 'Laptop, Notebook, Pen'
                ]
            ],
            // Event Cancelled
            [
                'type' => 'event',
                'title' => 'Event Cancelled',
                'message' => 'The AI & Machine Learning Seminar has been cancelled due to unforeseen circumstances.',
                'action_url' => '/events/4',
                'details' => [
                    'event_title' => 'AI & Machine Learning Seminar',
                    'original_date' => Carbon::now()->addDays(10)->format('M d, Y'),
                    'cancellation_reason' => 'Speaker unavailability',
                    'refund_status' => 'Full refund processed',
                    'reschedule_info' => 'Will be rescheduled for next month'
                ]
            ],
            // Event Certificate Available
            [
                'type' => 'event',
                'title' => 'Event Certificate Ready',
                'message' => 'Your participation certificate for the Python Workshop is now available.',
                'action_url' => '/certificates/5',
                'details' => [
                    'event_title' => 'Python Programming Workshop',
                    'certificate_type' => 'Participation',
                    'event_date' => Carbon::now()->subDays(7)->format('M d, Y'),
                    'attendance_hours' => '24 hours',
                    'download_available' => 'Yes'
                ]
            ]
        ];

        foreach ($eventNotifications as $notification) {
            $this->createNotification($user, $notification, Carbon::now()->subDays(rand(0, 20)));
        }
    }

    /**
     * Create membership-related notifications
     */
    private function createMembershipNotifications($user, $members)
    {
        $membershipNotifications = [
            // Membership Approved
            [
                'type' => 'member',
                'title' => 'Membership Approved',
                'message' => 'Welcome! Your membership application has been approved.',
                'action_url' => '/dashboard',
                'details' => [
                    'institution' => 'University of Dar es Salaam',
                    'member_type' => 'Student',
                    'approval_date' => Carbon::now()->subDays(30)->format('M d, Y'),
                    'approved_by' => 'Dr. Grace Mollel',
                    'membership_benefits' => 'Access to events, certificates, and resources'
                ]
            ],
            // Membership Rejected
            [
                'type' => 'member',
                'title' => 'Membership Rejected',
                'message' => 'Your membership application was not approved. Please contact admin for details.',
                'action_url' => '/members/application',
                'details' => [
                    'institution' => 'Sokoine University of Agriculture',
                    'rejection_reason' => 'Incomplete student verification documents',
                    'rejection_date' => Carbon::now()->subDays(45)->format('M d, Y'),
                    'reapplication_allowed' => 'Yes',
                    'contact_email' => 'admin@sua.ac.tz'
                ]
            ],
            // Membership Renewal Reminder
            [
                'type' => 'member',
                'title' => 'Membership Renewal Reminder',
                'message' => 'Your membership expires in 30 days. Please renew to continue access.',
                'action_url' => '/members/renew',
                'details' => [
                    'current_expiry' => Carbon::now()->addDays(30)->format('M d, Y'),
                    'renewal_fee' => 'TZS 25,000',
                    'renewal_benefits' => 'Continued access to all member benefits',
                    'late_fee' => 'TZS 5,000 after expiry date'
                ]
            ],
            // Role Assignment
            [
                'type' => 'member',
                'title' => 'New Role Assigned',
                'message' => 'You have been assigned a new role: Event Coordinator.',
                'action_url' => '/profile',
                'details' => [
                    'new_role' => 'Event Coordinator',
                    'previous_role' => 'Student Member',
                    'assigned_by' => 'Prof. Michael Kisangiri',
                    'assignment_date' => Carbon::now()->subDays(5)->format('M d, Y'),
                    'additional_permissions' => 'Event management, Member coordination'
                ]
            ]
        ];

        foreach ($membershipNotifications as $notification) {
            $this->createNotification($user, $notification, Carbon::now()->subDays(rand(0, 60)));
        }
    }

    /**
     * Create system notifications
     */
    private function createSystemNotifications($user)
    {
        $systemNotifications = [
            // System Maintenance
            [
                'type' => 'system',
                'title' => 'Scheduled System Maintenance',
                'message' => 'The system will be under maintenance on Sunday from 2:00 AM to 6:00 AM.',
                'action_url' => '/announcements',
                'details' => [
                    'maintenance_date' => Carbon::now()->addDays(3)->format('M d, Y'),
                    'maintenance_time' => '02:00 AM - 06:00 AM',
                    'affected_services' => 'All user functions temporarily unavailable',
                    'reason' => 'Database optimization and security updates',
                    'contact_support' => 'support@tra.go.tz'
                ]
            ],
            // Security Alert
            [
                'type' => 'system',
                'title' => 'Security Alert',
                'message' => 'New login detected from a different location. Verify if this was you.',
                'action_url' => '/security/sessions',
                'details' => [
                    'login_time' => Carbon::now()->subHours(3)->format('M d, Y H:i'),
                    'login_location' => 'Mwanza, Tanzania',
                    'device_info' => 'Chrome on Windows 10',
                    'ip_address' => '196.216.xxx.xxx',
                    'action_required' => 'Verify login or secure account'
                ]
            ],
            // Policy Update
            [
                'type' => 'system',
                'title' => 'Policy Update',
                'message' => 'Our Terms of Service and Privacy Policy have been updated.',
                'action_url' => '/legal/terms',
                'details' => [
                    'update_date' => Carbon::now()->subDays(7)->format('M d, Y'),
                    'major_changes' => 'Data retention, Certificate validity, User responsibilities',
                    'effective_date' => Carbon::now()->addDays(23)->format('M d, Y'),
                    'action_required' => 'Review and acknowledge updated policies'
                ]
            ],
            // Feature Update
            [
                'type' => 'system',
                'title' => 'New Features Available',
                'message' => 'We\'ve added new certificate templates and analytics dashboard.',
                'action_url' => '/features/new',
                'details' => [
                    'new_features' => 'Modern certificate templates, Advanced analytics, Mobile app improvements',
                    'release_date' => Carbon::now()->subDays(2)->format('M d, Y'),
                    'tutorial_available' => 'Yes',
                    'feedback_welcome' => 'Please share your experience'
                ]
            ]
        ];

        foreach ($systemNotifications as $notification) {
            $this->createNotification($user, $notification, Carbon::now()->subDays(rand(0, 10)));
        }
    }

    /**
     * Create general notifications
     */
    private function createGeneralNotifications($user)
    {
        $generalNotifications = [
            // Welcome Message
            [
                'type' => 'general',
                'title' => 'Welcome to TRA Platform',
                'message' => 'Welcome to the Tanzania Revenue Authority training platform. Explore available courses and events.',
                'action_url' => '/getting-started',
                'details' => [
                    'platform_features' => 'Event registration, Certificate management, Budget tracking',
                    'support_resources' => 'Help center, Video tutorials, FAQ section',
                    'contact_info' => 'support@tra.go.tz | +255 123 456 789',
                    'next_steps' => 'Complete your profile and browse available events'
                ]
            ],
            // Achievement Milestone
            [
                'type' => 'general',
                'title' => 'Achievement Unlocked',
                'message' => 'Congratulations! You\'ve completed 5 training programs this year.',
                'action_url' => '/achievements',
                'details' => [
                    'milestone' => '5 Training Programs Completed',
                    'achievement_date' => Carbon::now()->subDays(1)->format('M d, Y'),
                    'total_hours' => '120 training hours',
                    'next_milestone' => '10 programs (50% progress)',
                    'reward' => 'Special recognition certificate eligible'
                ]
            ],
            // Survey Request
            [
                'type' => 'general',
                'title' => 'Your Feedback Matters',
                'message' => 'Please take 5 minutes to complete our platform satisfaction survey.',
                'action_url' => '/surveys/satisfaction-2024',
                'details' => [
                    'survey_title' => 'Platform Satisfaction Survey 2024',
                    'estimated_time' => '5 minutes',
                    'deadline' => Carbon::now()->addDays(14)->format('M d, Y'),
                    'incentive' => 'Enter to win TZS 100,000 voucher',
                    'topics_covered' => 'User experience, Feature satisfaction, Improvement suggestions'
                ]
            ],
            // Newsletter
            [
                'type' => 'general',
                'title' => 'Monthly Newsletter',
                'message' => 'Check out this month\'s highlights: new courses, success stories, and upcoming events.',
                'action_url' => '/newsletter/march-2024',
                'details' => [
                    'newsletter_month' => 'March 2024',
                    'highlights' => 'New AI course, Student success stories, Upcoming tech conference',
                    'featured_story' => 'How TRA training helped Sarah advance her career',
                    'upcoming_events' => '5 events this month',
                    'special_offers' => 'Early bird registration discounts available'
                ]
            ],
            // Community Update
            [
                'type' => 'general',
                'title' => 'Community Update',
                'message' => 'Join our growing community! Connect with 500+ professionals in our network.',
                'action_url' => '/community',
                'details' => [
                    'total_members' => '500+ active professionals',
                    'new_members_this_month' => '45 new members',
                    'popular_discussions' => 'Career advancement, Technology trends, Best practices',
                    'networking_opportunities' => 'Monthly meetups, Online forums, Mentorship programs',
                    'community_guidelines' => 'Please review community guidelines'
                ]
            ]
        ];

        foreach ($generalNotifications as $notification) {
            $this->createNotification($user, $notification, Carbon::now()->subDays(rand(0, 40)));
        }
    }

    /**
     * Create a notification record
     */
    private function createNotification($user, $notificationData, $createdAt)
    {
        $isRead = rand(1, 100) <= 70; // 70% chance of being read

        DB::table('notifications')->insert([
            'id' => Str::uuid(),
            'type' => 'App\\Notifications\\' . ucfirst($notificationData['type']) . 'Notification',
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $user->id,
            'data' => json_encode($notificationData),
            'read_at' => $isRead ? $createdAt->addMinutes(rand(5, 2880)) : null, // Read within 2 days if read
            'created_at' => $createdAt,
            'updated_at' => $createdAt
        ]);
    }
}