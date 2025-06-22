<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'institution_id',
        'created_by',
        'start_date',
        'end_date',
        'venue',
        'address',
        'latitude',
        'longitude',
        'max_participants',
        'registration_fee',
        'is_free',
        'requires_approval',
        'allow_non_members',
        'registration_start',
        'registration_end',
        'status',
        'requirements',
        'objectives',
        'target_audience',
        'banner_image',
        'attachments',
        'tags',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'registration_fee' => 'decimal:2',
        'is_free' => 'boolean',
        'requires_approval' => 'boolean',
        'allow_non_members' => 'boolean',
        'requirements' => 'array',
        'objectives' => 'array',
        'target_audience' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
        'approved_at' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('title') && empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    /**
     * Get the institution that owns the event.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the user who created the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved the event.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all registrations for this event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get approved registrations for this event.
     */
    public function approvedRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'approved');
    }

    /**
     * Get pending registrations for this event.
     */
    public function pendingRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class)->where('status', 'pending');
    }

    /**
     * Get attended registrations for this event.
     */
    public function attendedRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class)->where('attended', true);
    }

    /**
     * Check if event is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if event is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if event is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if event is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if event is upcoming.
     */
    public function isUpcoming(): bool
    {
        return $this->start_date > now() && $this->isPublished();
    }

    /**
     * Check if event is ongoing.
     */
    public function isOngoing(): bool
    {
        return $this->start_date <= now() && $this->end_date >= now() && $this->isPublished();
    }

    /**
     * Check if event is past.
     */
    public function isPast(): bool
    {
        return $this->end_date < now();
    }

    /**
     * Check if registration is open.
     */
    public function isRegistrationOpen(): bool
    {
        if (!$this->isPublished()) {
            return false;
        }

        $now = now();
        
        if ($this->registration_start && $now < $this->registration_start) {
            return false;
        }
        
        if ($this->registration_end && $now > $this->registration_end) {
            return false;
        }
        
        if ($this->max_participants && $this->approvedRegistrations()->count() >= $this->max_participants) {
            return false;
        }
        
        return true;
    }

    /**
     * Get event type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match ($this->type) {
            'workshop' => 'Workshop',
            'seminar' => 'Seminar',
            'training' => 'Training',
            'conference' => 'Conference',
            'meeting' => 'Meeting',
            'competition' => 'Competition',
            'other' => 'Other',
            default => 'Event',
        };
    }

    /**
     * Get event status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'published' => 'Published',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            'postponed' => 'Postponed',
            default => 'Unknown',
        };
    }

    /**
     * Get event banner URL.
     */
    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_image) {
            return asset('storage/' . $this->banner_image);
        }
        
        // Generate a placeholder banner
        return "https://ui-avatars.com/api/?name=" . urlencode($this->title) . "&color=000000&background=F9E510&size=400x200&format=png";
    }

    /**
     * Get available spots.
     */
    public function getAvailableSpotsAttribute(): ?int
    {
        if (!$this->max_participants) {
            return null;
        }
        
        return $this->max_participants - $this->approvedRegistrations()->count();
    }

    /**
     * Get registration count.
     */
    public function getRegistrationCountAttribute(): int
    {
        return $this->registrations()->count();
    }

    /**
     * Get attendance rate.
     */
    public function getAttendanceRateAttribute(): float
    {
        $totalRegistrations = $this->approvedRegistrations()->count();
        if ($totalRegistrations === 0) {
            return 0;
        }
        
        $attendedCount = $this->attendedRegistrations()->count();
        return round(($attendedCount / $totalRegistrations) * 100, 1);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to get upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())->published();
    }

    /**
     * Scope to get past events.
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    /**
     * Scope to filter by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by institution.
     */
    public function scopeInstitution($query, int $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    /**
     * Scope to search events.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('venue', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }
}