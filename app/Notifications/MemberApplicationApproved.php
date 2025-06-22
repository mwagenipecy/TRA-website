<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Member;

class MemberApplicationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Membership Application Approved')
            ->greeting('Congratulations!')
            ->line('Your membership application has been approved.')
            ->line('Institution: ' . $this->member->institution->name)
            ->line('Member Type: ' . ucfirst($this->member->member_type))
            ->line('You can now access all member features and participate in organizational activities.')
            ->action('Login to Your Account', url('/login'))
            ->line('Welcome to the organization!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Membership Approved',
            'message' => 'Your membership application has been approved for ' . $this->member->institution->name,
            'member_id' => $this->member->id,
            'type' => 'approval',
        ];
    }
}

class MemberApplicationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    protected $member;
    protected $reason;

    public function __construct(Member $member, $reason = null)
    {
        $this->member = $member;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Membership Application Update')
            ->greeting('Hello!')
            ->line('We have reviewed your membership application for ' . $this->member->institution->name . '.')
            ->line('Unfortunately, we are unable to approve your application at this time.');

        if ($this->reason) {
            $mail->line('Reason: ' . $this->reason);
        }

        return $mail->line('You may reapply in the future or contact us for more information.')
                   ->line('Thank you for your interest in our organization.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Membership Application Update',
            'message' => 'Your membership application for ' . $this->member->institution->name . ' requires attention',
            'member_id' => $this->member->id,
            'type' => 'rejection',
            'reason' => $this->reason,
        ];
    }
}

class NewMemberApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $member;

    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Member Application Received')
            ->greeting('Hello!')
            ->line('A new member application has been received and requires your review.')
            ->line('Applicant: ' . $this->member->user->name)
            ->line('Institution: ' . $this->member->institution->name)
            ->line('Member Type: ' . ucfirst($this->member->member_type))
            ->action('Review Application', route('members.pending'))
            ->line('Please review and take appropriate action.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Member Application',
            'message' => $this->member->user->name . ' has applied for membership at ' . $this->member->institution->name,
            'member_id' => $this->member->id,
            'type' => 'new_application',
        ];
    }
}