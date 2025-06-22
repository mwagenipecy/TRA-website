<?php

// database/seeders/CertificateSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;
use App\Models\User;
use App\Models\Institution;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CertificateSeeder extends Seeder
{
    private $certificateTemplates = [
        'completion' => [
            [
                'title' => 'Web Development Bootcamp - Certificate of Completion',
                'description' => 'This certificate acknowledges the successful completion of a comprehensive 12-week web development bootcamp covering HTML, CSS, JavaScript, React, and Node.js.',
                'course_name' => 'Full Stack Web Development Bootcamp',
                'duration' => '12 weeks (480 hours)',
                'grade' => 'A',
                'achievement_details' => 'Successfully completed all modules including frontend development, backend programming, database management, and final capstone project.',
                'instructor' => 'Dr. James Mwangi'
            ],
            [
                'title' => 'Digital Marketing Mastery - Completion Certificate',
                'description' => 'Certificate awarded for completing advanced digital marketing training covering SEO, SEM, social media marketing, and analytics.',
                'course_name' => 'Digital Marketing Professional Program',
                'duration' => '8 weeks (320 hours)',
                'grade' => 'B+',
                'achievement_details' => 'Demonstrated proficiency in Google Ads, Facebook Marketing, Content Strategy, and ROI Analysis.',
                'instructor' => 'Ms. Sarah Kimaro'
            ],
            [
                'title' => 'Data Science and Analytics - Certificate of Completion',
                'description' => 'Comprehensive certification in data science methodologies, statistical analysis, machine learning, and data visualization.',
                'course_name' => 'Professional Data Science Program',
                'duration' => '16 weeks (640 hours)',
                'grade' => 'A-',
                'achievement_details' => 'Mastered Python, R, SQL, Tableau, and completed 3 real-world data science projects.',
                'instructor' => 'Prof. Grace Mollel'
            ],
            [
                'title' => 'Project Management Professional - Completion',
                'description' => 'Certificate for completing PMP preparation course covering all knowledge areas and process groups.',
                'course_name' => 'Project Management Professional (PMP) Prep',
                'duration' => '10 weeks (400 hours)',
                'grade' => 'A',
                'achievement_details' => 'Covered initiating, planning, executing, monitoring, and closing project phases with practical case studies.',
                'instructor' => 'Eng. Michael Kisangiri'
            ],
            [
                'title' => 'Cybersecurity Fundamentals - Certificate of Completion',
                'description' => 'Foundational cybersecurity training covering network security, ethical hacking, and incident response.',
                'course_name' => 'Cybersecurity Essentials Program',
                'duration' => '6 weeks (240 hours)',
                'grade' => 'B+',
                'achievement_details' => 'Learned vulnerability assessment, penetration testing, and security policy development.',
                'instructor' => 'Dr. Anna Mwalimu'
            ]
        ],
        'participation' => [
            [
                'title' => 'Annual Technology Conference 2024 - Participation Certificate',
                'description' => 'Certificate of participation in the largest technology conference in East Africa featuring 50+ speakers and 100+ sessions.',
                'course_name' => 'TechConf EA 2024',
                'duration' => '3 days',
                'grade' => 'Participated',
                'achievement_details' => 'Attended keynote sessions on AI, participated in workshops on cloud computing, and networked with 500+ professionals.',
                'instructor' => 'Various Industry Experts'
            ],
            [
                'title' => 'Leadership Workshop Series - Participation',
                'description' => 'Participated in intensive leadership development workshop focusing on team management and strategic thinking.',
                'course_name' => 'Executive Leadership Development',
                'duration' => '2 days (16 hours)',
                'grade' => 'Participated',
                'achievement_details' => 'Engaged in leadership simulations, team building exercises, and strategic planning sessions.',
                'instructor' => 'Dr. Robert Nyerere'
            ],
            [
                'title' => 'Innovation and Entrepreneurship Summit - Participation',
                'description' => 'Active participation in entrepreneurship summit featuring startup pitches, investor panels, and innovation workshops.',
                'course_name' => 'Innovation Summit 2024',
                'duration' => '2 days',
                'grade' => 'Participated',
                'achievement_details' => 'Participated in startup pitch sessions, attended investor panels, and completed business model canvas workshop.',
                'instructor' => 'Multiple Industry Leaders'
            ],
            [
                'title' => 'Research Methodology Workshop - Participation',
                'description' => 'Participated in comprehensive research methodology training for academic and professional research.',
                'course_name' => 'Advanced Research Methods',
                'duration' => '5 days (40 hours)',
                'grade' => 'Participated',
                'achievement_details' => 'Learned quantitative and qualitative research methods, data collection techniques, and analysis tools.',
                'instructor' => 'Prof. Mary Mchome'
            ]
        ],
        'achievement' => [
            [
                'title' => 'Outstanding Academic Performance - Excellence Award',
                'description' => 'Recognition for maintaining exceptional academic performance with GPA above 3.8 throughout the program.',
                'course_name' => 'Computer Science Program',
                'duration' => '4 years',
                'grade' => 'Summa Cum Laude',
                'achievement_details' => 'Maintained 3.95 GPA, published 2 research papers, and received Dean\'s List recognition for 8 consecutive semesters.',
                'instructor' => 'Academic Excellence Committee'
            ],
            [
                'title' => 'Innovation Competition Winner - First Place',
                'description' => 'First place winner in the National Innovation Competition for developing a mobile app solving local transportation challenges.',
                'course_name' => 'National Innovation Challenge 2024',
                'duration' => '6 months development',
                'grade' => '1st Place',
                'achievement_details' => 'Developed "SafeBoda" app with 10,000+ downloads, secured TZS 50M funding, and created 25 jobs.',
                'instructor' => 'Innovation Panel Judges'
            ],
            [
                'title' => 'Best Research Paper Award - Graduate Studies',
                'description' => 'Recognition for outstanding research contribution in artificial intelligence and machine learning applications.',
                'course_name' => 'Masters in Computer Science',
                'duration' => '2 years research',
                'grade' => 'Best Paper Award',
                'achievement_details' => 'Research on "AI-powered Disease Diagnosis" published in international journal with 95% accuracy results.',
                'instructor' => 'Research Supervision Committee'
            ],
            [
                'title' => 'Community Service Excellence Award',
                'description' => 'Recognition for exceptional community service and volunteer work impacting over 1,000 community members.',
                'course_name' => 'Community Outreach Program',
                'duration' => '2 years service',
                'grade' => 'Excellence',
                'achievement_details' => 'Led 15 community projects, trained 200+ volunteers, and established 3 permanent community centers.',
                'instructor' => 'Community Service Board'
            ]
        ],
        'recognition' => [
            [
                'title' => 'Volunteer Service Recognition - Community Impact',
                'description' => 'Recognition for dedicated volunteer service in educational technology initiatives across rural communities.',
                'course_name' => 'Rural Education Technology Initiative',
                'duration' => '18 months',
                'grade' => 'Distinguished Service',
                'achievement_details' => 'Established computer labs in 12 rural schools, trained 150 teachers, and impacted 3,000+ students.',
                'instructor' => 'Ministry of Education'
            ],
            [
                'title' => 'Mentorship Excellence Recognition',
                'description' => 'Special recognition for outstanding mentorship of junior students and early-career professionals.',
                'course_name' => 'Professional Mentorship Program',
                'duration' => '3 years',
                'grade' => 'Excellence',
                'achievement_details' => 'Mentored 45 students, achieved 95% job placement rate, and established mentorship best practices.',
                'instructor' => 'Professional Development Office'
            ],
            [
                'title' => 'Industry Collaboration Award',
                'description' => 'Recognition for facilitating successful partnerships between academia and industry, creating internship opportunities.',
                'course_name' => 'University-Industry Partnership',
                'duration' => '2 years',
                'grade' => 'Outstanding',
                'achievement_details' => 'Secured partnerships with 20+ companies, created 200+ internship positions, and achieved 85% job conversion rate.',
                'instructor' => 'Industry Relations Committee'
            ],
            [
                'title' => 'Alumni Achievement Recognition',
                'description' => 'Recognition as distinguished alumni for professional achievements and continued contribution to alma mater.',
                'course_name' => 'Distinguished Alumni Program',
                'duration' => 'Career achievement',
                'grade' => 'Distinguished',
                'achievement_details' => 'Founded successful tech company, created 500+ jobs, and donated TZS 100M for scholarship fund.',
                'instructor' => 'Alumni Relations Office'
            ]
        ]
    ];

    private $institutions = [];
    private $users = [];
    private $events = [];

    public function run()
    {

        $this->command->info('No users found. Please run UserSeeder first.');

        $this->loadRequiredData();
        
        if (empty($this->users)) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        if (empty($this->institutions)) {
            $this->command->info('No institutions found. Please run InstitutionSeeder first.');
            return;
        }

        $this->command->info('Creating certificate dump data...');

        // Create certificates for each institution
        foreach ($this->institutions as $institution) {
            $this->createInstitutionCertificates($institution);
        }

        // Create some system-wide certificates (TRA issued)
        $this->createSystemCertificates();

        $this->command->info('Certificate dump data created successfully!');
        $this->command->info('Total certificates created: ' . Certificate::count());
    }

    private function loadRequiredData()
    {
        $this->institutions = Institution::get(); //where('status', 'active')->get();
        $this->users = User::get(); //where('status', 'active')->get();
        $this->events = Event::get(); //where('status', 'completed')->get();
    }

    private function createInstitutionCertificates($institution)
    {
        // Get users from this institution
        $institutionUsers = $this->users->filter(function($user) use ($institution) {
            return $user->members->where('institution_id', $institution->id)
                                ->where('status', 'active')
                                ->isNotEmpty();
        });

        if ($institutionUsers->isEmpty()) {
            return;
        }

        // Get potential issuers (leaders, supervisors, tra_officers)
        $issuers = $institutionUsers->whereIn('role', ['leader', 'supervisor'])
                                   ->merge($this->users->where('role', 'tra_officer'));

        if ($issuers->isEmpty()) {
            $issuers = $institutionUsers->take(1); // Fallback to any user
        }

        $certificatesPerInstitution = rand(25, 50);

        for ($i = 0; $i < $certificatesPerInstitution; $i++) {
            $this->createSingleCertificate($institution, $institutionUsers, $issuers);
        }
    }

    private function createSingleCertificate($institution, $users, $issuers)
    {
        $type = $this->getRandomCertificateType();
        $template = $this->getRandomTemplate($type);
        $recipient = $users->random();
        $issuer = $issuers->random();
        $event = $this->events->isNotEmpty() && rand(1, 3) === 1 ? $this->events->random() : null;

        // Generate realistic dates
        $issueDate = $this->getRandomIssueDate();
        $expiryDate = $this->getExpiryDate($type, $issueDate);

        // Determine status
        $status = $this->getCertificateStatus($issueDate, $expiryDate);

        $certificateCode = $this->generateCertificateCode($institution, $type, $issueDate);
        $verificationHash = $this->generateVerificationHash();

        $certificate = Certificate::create([
            'certificate_code' => $certificateCode,
            'title' => $template['title'],
            'description' => $template['description'],
            'type' => $type,
            'user_id' => $recipient->id,
            'event_id' => $event?->id,
            'institution_id' => $institution->id,
            'issued_by' => $issuer->id,
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'status' => $status['current_status'],
            'verification_hash' => $verificationHash,
            'certificate_data' => json_encode([
                'course_name' => $template['course_name'],
                'duration' => $template['duration'],
                'grade' => $template['grade'],
                'instructor' => $template['instructor'],
                'achievement_details' => $template['achievement_details']
            ]),
            'template_used' => $this->getRandomTemplateStyle(),
            'special_notes' => $this->getSpecialNotes($type),
            'file_path' => null, // Could be populated with actual file paths
            'revoked_by' => $status['revoked_by'],
            'revoked_at' => $status['revoked_at'],
            'revocation_reason' => $status['revocation_reason'],
            'created_at' => $issueDate,
            'updated_at' => $status['revoked_at'] ?? $issueDate
        ]);

        return $certificate;
    }

    private function createSystemCertificates()
    {
        $traOfficers = $this->users->where('role', 'tra_officer');
        if ($traOfficers->isEmpty()) {
            return;
        }

        $systemCertificates = [
            [
                'title' => 'TRA Excellence in Public Service Award',
                'description' => 'Highest recognition for exceptional contribution to public service and revenue administration excellence.',
                'type' => 'achievement',
                'course_name' => 'Public Service Excellence Program',
                'duration' => 'Career Achievement',
                'grade' => 'Outstanding',
                'achievement_details' => 'Demonstrated exceptional leadership in revenue collection, policy implementation, and public service delivery.',
                'instructor' => 'TRA Commissioner General'
            ],
            [
                'title' => 'National Tax Administration Certificate',
                'description' => 'Advanced certification in tax administration, policy development, and revenue optimization strategies.',
                'type' => 'completion',
                'course_name' => 'Advanced Tax Administration',
                'duration' => '6 months (240 hours)',
                'grade' => 'A+',
                'achievement_details' => 'Mastered tax policy analysis, audit procedures, and digital transformation in revenue administration.',
                'instructor' => 'International Tax Academy'
            ],
            [
                'title' => 'Regional Leadership Forum - Participation',
                'description' => 'Participated in East African Revenue Authorities leadership forum addressing regional tax harmonization.',
                'type' => 'participation',
                'course_name' => 'EAC Revenue Leadership Summit',
                'duration' => '5 days',
                'grade' => 'Participated',
                'achievement_details' => 'Contributed to regional tax policy discussions and cross-border taxation frameworks.',
                'instructor' => 'EAC Secretariat'
            ]
        ];

        foreach ($systemCertificates as $certData) {
            $this->createSystemCertificate($certData, $traOfficers);
        }
    }

    private function createSystemCertificate($certData, $traOfficers)
    {
        $numCertificates = rand(3, 8);
        
        for ($i = 0; $i < $numCertificates; $i++) {
            $recipient = $this->users->random();
            $issuer = $traOfficers->random();
            $institution = $recipient->currentInstitution ?? $this->institutions->random();

            $issueDate = $this->getRandomIssueDate();
            $expiryDate = $this->getExpiryDate($certData['type'], $issueDate);
            $status = $this->getCertificateStatus($issueDate, $expiryDate);

            Certificate::create([
                'certificate_code' => $this->generateCertificateCode($institution, $certData['type'], $issueDate),
                'title' => $certData['title'],
                'description' => $certData['description'],
                'type' => $certData['type'],
                'user_id' => $recipient->id,
                'event_id' => null,
                'institution_id' => $institution->id,
                'issued_by' => $issuer->id,
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate,
                'status' => $status['current_status'],
                'verification_hash' => $this->generateVerificationHash(),
                'certificate_data' => json_encode([
                    'course_name' => $certData['course_name'],
                    'duration' => $certData['duration'],
                    'grade' => $certData['grade'],
                    'instructor' => $certData['instructor'],
                    'achievement_details' => $certData['achievement_details']
                ]),
                'template_used' => 'formal',
                'special_notes' => 'Issued by Tanzania Revenue Authority',
                'file_path' => null,
                'revoked_by' => $status['revoked_by'],
                'revoked_at' => $status['revoked_at'],
                'revocation_reason' => $status['revocation_reason'],
                'created_at' => $issueDate,
                'updated_at' => $status['revoked_at'] ?? $issueDate
            ]);
        }
    }

    private function getRandomCertificateType()
    {
        $types = ['completion', 'participation', 'achievement', 'recognition'];
        $weights = [50, 30, 15, 5]; // Completion is most common
        
        $random = rand(1, 100);
        $cumulative = 0;
        
        foreach ($weights as $index => $weight) {
            $cumulative += $weight;
            if ($random <= $cumulative) {
                return $types[$index];
            }
        }
        
        return 'completion';
    }

    private function getRandomTemplate($type)
    {
        return $this->certificateTemplates[$type][array_rand($this->certificateTemplates[$type])];
    }

    private function getRandomTemplateStyle()
    {
        $templates = ['default', 'formal', 'modern', 'classic'];
        return $templates[array_rand($templates)];
    }

    private function getRandomIssueDate()
    {
        // Random date within the last 3 years
        return Carbon::now()->subDays(rand(1, 1095));
    }

    private function getExpiryDate($type, $issueDate)
    {
        // Different expiry patterns based on type
        switch ($type) {
            case 'completion':
                // Some completion certificates expire, some don't
                return rand(1, 3) === 1 ? $issueDate->copy()->addYears(rand(2, 5)) : null;
            
            case 'participation':
                // Participation certificates usually don't expire
                return rand(1, 10) === 1 ? $issueDate->copy()->addYears(rand(3, 10)) : null;
            
            case 'achievement':
                // Achievement certificates usually don't expire
                return null;
            
            case 'recognition':
                // Recognition certificates usually don't expire
                return null;
            
            default:
                return null;
        }
    }

    private function getCertificateStatus($issueDate, $expiryDate)
    {
        // Most certificates are active
        $randomStatus = rand(1, 100);
        
        if ($randomStatus <= 85) {
            // 85% active
            return [
                'current_status' => 'active',
                'revoked_by' => null,
                'revoked_at' => null,
                'revocation_reason' => null
            ];
        } else {
            // 15% revoked
            $revokedDate = $issueDate->copy()->addDays(rand(30, 365));
            $revoker = $this->users->where('role', 'tra_officer')->random() ?? $this->users->random();
            
            $revocationReasons = [
                'Policy violation discovered during audit',
                'False information provided during application',
                'Duplicate certificate issued in error',
                'Institutional request for revocation',
                'Failed to meet continuing education requirements',
                'Administrative error correction',
                'Disciplinary action by institution'
            ];
            
            return [
                'current_status' => 'revoked',
                'revoked_by' => $revoker->id,
                'revoked_at' => $revokedDate,
                'revocation_reason' => $revocationReasons[array_rand($revocationReasons)]
            ];
        }
    }

    private function getSpecialNotes($type)
    {
        $notes = [
            'completion' => [
                'Awarded with distinction for exceptional performance',
                'Completed with honors - top 10% of cohort',
                'Achieved perfect attendance throughout program',
                'Demonstrated outstanding practical application',
                'Exceeded all assessment requirements',
                null, null, null // Some certificates have no special notes
            ],
            'participation' => [
                'Active participant in all sessions',
                'Contributed significantly to group discussions',
                'Excellent engagement and networking',
                'Perfect attendance record',
                null, null, null, null
            ],
            'achievement' => [
                'Exceptional achievement recognized by panel',
                'Outstanding contribution to field of study',
                'Innovation award recipient',
                'Research excellence demonstrated',
                'Leadership qualities exhibited throughout'
            ],
            'recognition' => [
                'Community impact recognized by peers',
                'Volunteer service above and beyond',
                'Mentorship excellence acknowledged',
                'Distinguished service to institution',
                'Professional excellence in field'
            ]
        ];
        
        return $notes[$type][array_rand($notes[$type])];
    }

    private function generateCertificateCode($institution, $type, $issueDate)
    {
        $prefix = strtoupper(substr($institution->code, 0, 3));
        $year = $issueDate->format('Y');
        $typeCode = strtoupper(substr($type, 0, 3));
        
        // Get existing count to ensure uniqueness
        $existingCount = Certificate::where('institution_id', $institution->id)
            ->whereYear('issue_date', $year)
            ->where('type', $type)
            ->count();
            
        $number = str_pad($existingCount + 1, 4, '0', STR_PAD_LEFT);
        
        $code = "{$prefix}-{$year}-{$typeCode}-{$number}";
        
        // Ensure uniqueness
        while (Certificate::where('certificate_code', $code)->exists()) {
            $number = str_pad((int)$number + 1, 4, '0', STR_PAD_LEFT);
            $code = "{$prefix}-{$year}-{$typeCode}-{$number}";
        }
        
        return $code;
    }

    private function generateVerificationHash()
    {
        do {
            $hash = hash('sha256', Str::random(40) . microtime() . mt_rand());
        } while (Certificate::where('verification_hash', $hash)->exists());
        
        return $hash;
    }
}