<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Certificate;

class CertificateRevoked extends Notification implements ShouldQueue
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
            ->subject('Certificate Revoked - ' . $this->certificate->title)
            ->line('Your certificate has been revoked.')
            ->line('Certificate: ' . $this->certificate->title)
            ->line('Certificate Code: ' . $this->certificate->certificate_code)
            ->line('Revocation Date: ' . $this->certificate->revoked_at->format('F d, Y'))
            ->when($this->certificate->revocation_reason, function($mail) {
                return $mail->line('Reason: ' . $this->certificate->revocation_reason);
            })
            ->action('View Certificate', route('certificates.show', $this->certificate->id))
            ->line('If you have questions about this revocation, please contact the issuing institution.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'certificate',
            'title' => 'Certificate Revoked',
            'message' => "Your certificate '{$this->certificate->title}' has been revoked",
            'certificate_id' => $this->certificate->id,
            'certificate_code' => $this->certificate->certificate_code,
            'action_url' => route('certificates.show', $this->certificate->id),
            'details' => [
                'certificate_title' => $this->certificate->title,
                'revocation_date' => $this->certificate->revoked_at->format('M d, Y'),
                'revocation_reason' => $this->certificate->revocation_reason ?? 'No reason provided'
            ]
        ];
    }
}