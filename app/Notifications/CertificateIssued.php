<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Certificate;

class CertificateIssued extends Notification implements ShouldQueue
{
    use Queueable;

    protected $certificate;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Certificate Issued - ' . $this->certificate->title)
            ->greeting('Congratulations!')
            ->line('You have been issued a new certificate.')
            ->line('Certificate: ' . $this->certificate->title)
            ->line('Certificate Code: ' . $this->certificate->certificate_code)
            ->line('Issue Date: ' . $this->certificate->issue_date->format('F d, Y'))
            ->action('View Certificate', route('certificates.show', $this->certificate->id))
            ->line('Thank you for your participation!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'certificate',
            'title' => 'New Certificate Issued',
            'message' => "You have been issued a certificate: {$this->certificate->title}",
            'certificate_id' => $this->certificate->id,
            'certificate_code' => $this->certificate->certificate_code,
            'action_url' => route('certificates.show', $this->certificate->id),
            'details' => [
                'certificate_title' => $this->certificate->title,
                'certificate_type' => ucfirst($this->certificate->type),
                'issue_date' => $this->certificate->issue_date->format('M d, Y'),
                'institution' => $this->certificate->institution->name
            ]
        ];
    }
}