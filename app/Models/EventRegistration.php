<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'event_id',
        'user_id',
        'is_member',
        'status',
        'registered_at',
        'approved_at',
        'approved_by',
        'approval_notes',
        'additional_info',
        'payment_required',
        'payment_status',
        'amount_paid',
        'payment_reference',
        'payment_date',
        'attended',
        'check_in_time',
        'check_out_time',
        'rating',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_member' => 'boolean',
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
        'additional_info' => 'array',
        'payment_required' => 'boolean',
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
        'attended' => 'boolean',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->registered_at)) {
                $registration->registered_at = now();
            }
        });
    }

    /**
     * Get the event this registration belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who registered.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved this registration.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if registration is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if registration is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if registration is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if registration is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if user attended the event.
     */
    public function hasAttended(): bool
    {
        return $this->attended === true;
    }

    /**
     * Check if payment is required and not paid.
     */
    public function isPaymentPending(): bool
    {
        return $this->payment_required && $this->payment_status !== 'paid';
    }

    /**
     * Get registration status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Approval',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
            'attended' => 'Attended',
            'no_show' => 'No Show',
            default => 'Unknown',
        };
    }

    /**
     * Get payment status display name.
     */
    public function getPaymentStatusDisplayAttribute(): string
    {
        if (!$this->payment_required) {
            return 'Not Required';
        }

        return match ($this->payment_status) {
            'pending' => 'Payment Pending',
            'paid' => 'Paid',
            'failed' => 'Payment Failed',
            'refunded' => 'Refunded',
            default => 'Unknown',
        };
    }

    /**
     * Get attendance duration in minutes.
     */
    public function getAttendanceDurationAttribute(): ?int
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }

        return $this->check_in_time->diffInMinutes($this->check_out_time);
    }

    /**
     * Approve the registration.
     */
    public function approve(?User $approver = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approver?->id ?? auth()->id(),
            'approval_notes' => $notes,
        ]);
    }

    /**
     * Reject the registration.
     */
    public function reject(?User $approver = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_at' => now(),
            'approved_by' => $approver?->id ?? auth()->id(),
            'approval_notes' => $notes,
        ]);
    }

    /**
     * Mark as attended.
     */
    public function markAttended(): void
    {
        $this->update([
            'attended' => true,
            'status' => 'attended',
            'check_in_time' => now(),
        ]);
    }

    /**
     * Mark check out.
     */
    public function markCheckOut(): void
    {
        $this->update([
            'check_out_time' => now(),
        ]);
    }

    /**
     * Mark as no show.
     */
    public function markNoShow(): void
    {
        $this->update([
            'status' => 'no_show',
        ]);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending registrations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get approved registrations.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get attended registrations.
     */
    public function scopeAttended($query)
    {
        return $query->where('attended', true);
    }

    /**
     * Scope to filter by payment status.
     */
    public function scopePaymentStatus($query, string $status)
    {
        return $query->where('payment_status', $status);
    }
}