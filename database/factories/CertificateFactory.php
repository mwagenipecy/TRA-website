<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\User;
use App\Models\Institution;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class CertificateFactory extends Factory
{
    protected $model = Certificate::class;

    public function definition()
    {
        $type = $this->faker->randomElement(['completion', 'participation', 'achievement', 'recognition']);
        $issueDate = $this->faker->dateTimeBetween('-2 years', 'now');
        
        return [
            'certificate_code' => $this->generateCertificateCode(),
            'title' => $this->generateTitle($type),
            'description' => $this->faker->paragraph(3),
            'type' => $type,
            'user_id' => User::factory(),
            'event_id' => $this->faker->optional(0.3)->randomElement(Event::pluck('id')->toArray()),
            'institution_id' => Institution::factory(),
            'issued_by' => User::factory(),
            'issue_date' => $issueDate,
            'expiry_date' => $this->faker->optional(0.3)->dateTimeBetween($issueDate, '+5 years'),
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'active', 'revoked']), // 80% active
            'verification_hash' => hash('sha256', $this->faker->unique()->uuid),
            'certificate_data' => json_encode([
                'course_name' => $this->faker->sentence(4),
                'duration' => $this->faker->randomElement(['2 weeks', '1 month', '3 months', '6 months']),
                'grade' => $this->faker->randomElement(['A+', 'A', 'A-', 'B+', 'B']),
                'instructor' => $this->faker->name
            ]),
            'template_used' => $this->faker->randomElement(['default', 'formal', 'modern', 'classic']),
            'special_notes' => $this->faker->optional(0.4)->sentence(),
        ];
    }

    private function generateCertificateCode()
    {
        return strtoupper($this->faker->lexify('???')) . '-' . 
               date('Y') . '-' . 
               strtoupper($this->faker->lexify('???')) . '-' . 
               str_pad($this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    private function generateTitle($type)
    {
        $titles = [
            'completion' => [
                'Advanced Web Development - Certificate of Completion',
                'Data Science Bootcamp - Completion Certificate',
                'Digital Marketing Mastery - Certificate of Completion'
            ],
            'participation' => [
                'Tech Conference 2024 - Participation Certificate',
                'Innovation Workshop - Certificate of Participation',
                'Leadership Summit - Participation Certificate'
            ],
            'achievement' => [
                'Excellence in Programming - Achievement Award',
                'Outstanding Academic Performance - Achievement Certificate',
                'Innovation Competition Winner - Achievement Recognition'
            ],
            'recognition' => [
                'Community Service Recognition Award',
                'Volunteer Excellence - Recognition Certificate',
                'Professional Contribution - Recognition Award'
            ]
        ];
        
        return $this->faker->randomElement($titles[$type]);
    }

    public function revoked()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'revoked',
                'revoked_by' => User::factory(),
                'revoked_at' => $this->faker->dateTimeBetween($attributes['issue_date'], 'now'),
                'revocation_reason' => $this->faker->sentence()
            ];
        });
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'expiry_date' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
            ];
        });
    }
}