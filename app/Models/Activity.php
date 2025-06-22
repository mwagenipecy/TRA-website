<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'description',
        'user_id',
        'institution_id',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
        'performed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'properties' => 'array',
        'performed_at' => 'datetime',
    ];

    /**
     * Get the user who performed the activity.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who performed the activity (alias for compatibility).
     * This provides compatibility with spatie/laravel-activitylog package naming.
     */
    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the institution related to the activity.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the subject of the activity (polymorphic).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Create an activity log entry.
     */
    public static function log(string $type, string $description, ?User $user = null, ?Model $subject = null, ?Institution $institution = null, array $properties = []): self
    {
        return self::create([
            'type' => $type,
            'description' => $description,
            'user_id' => $user?->id ?? auth()->id(),
            'institution_id' => $institution?->id ?? ($user?->member?->institution_id),
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => now(),
        ]);
    }

    /**
     * Scope to filter by activity type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to filter by institution.
     */
    public function scopeByInstitution($query, int $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('performed_at', [$startDate, $endDate]);
    }

    /**
     * Get activity type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match ($this->type) {
            'user_registered' => 'User Registration',
            'event_created' => 'Event Created',
            'event_registered' => 'Event Registration',
            'budget_submitted' => 'Budget Submitted',
            'budget_approved' => 'Budget Approved',
            'budget_rejected' => 'Budget Rejected',
            'member_approved' => 'Member Approved',
            'institution_approved' => 'Institution Approved',
            'certificate_issued' => 'Certificate Issued',
            'login' => 'User Login',
            'logout' => 'User Logout',
            'created' => 'Record Created',
            'updated' => 'Record Updated',
            'deleted' => 'Record Deleted',
            'exported' => 'Data Exported',
            'imported' => 'Data Imported',
            default => 'System Activity',
        };
    }

    /**
     * Get activity icon.
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'user_registered' => 'fas fa-user-plus',
            'event_created' => 'fas fa-calendar-plus',
            'event_registered' => 'fas fa-calendar-check',
            'budget_submitted' => 'fas fa-file-invoice-dollar',
            'budget_approved' => 'fas fa-check-circle',
            'budget_rejected' => 'fas fa-times-circle',
            'member_approved' => 'fas fa-user-check',
            'institution_approved' => 'fas fa-university',
            'certificate_issued' => 'fas fa-certificate',
            'login' => 'fas fa-sign-in-alt',
            'logout' => 'fas fa-sign-out-alt',
            'created' => 'fas fa-plus-circle',
            'updated' => 'fas fa-edit',
            'deleted' => 'fas fa-trash',
            'exported' => 'fas fa-download',
            'imported' => 'fas fa-upload',
            default => 'fas fa-info-circle',
        };
    }

    /**
     * Get event attribute (alias for type for compatibility).
     */
    public function getEventAttribute(): ?string
    {
        return $this->type;
    }

    /**
     * Get causer_id attribute (alias for user_id for compatibility).
     */
    public function getCauserIdAttribute(): ?int
    {
        return $this->user_id;
    }

    /**
     * Get extra property from properties array.
     */
    public function getExtraProperty(string $key, $default = null)
    {
        return data_get($this->properties, $key, $default);
    }

    /**
     * Set extra property in properties array.
     */
    public function setExtraProperty(string $key, $value): self
    {
        $properties = $this->properties ?? [];
        data_set($properties, $key, $value);
        $this->properties = $properties;
        return $this;
    }

    /**
     * Get the created_at attribute (alias for performed_at for compatibility).
     */
    public function getCreatedAtAttribute()
    {
        return $this->performed_at;
    }

    /**
     * Get batch_uuid attribute (for compatibility, returns null as not in schema).
     */
    public function getBatchUuidAttribute(): ?string
    {
        return $this->getExtraProperty('batch_uuid');
    }
}