<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institution extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'type',
        'description',
        'address',
        'city',
        'region',
        'postal_code',
        'phone',
        'email',
        'website',
        'logo',
        'established_date',
        'status',
        'contact_persons',
        'created_by',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'established_date' => 'date',
        'contact_persons' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get all members of this institution.
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get active members of this institution.
     */
    public function activeMembers(): HasMany
    {
        return $this->hasMany(Member::class)->where('status', 'active');
    }

    /**
     * Get pending members of this institution.
     */
    public function pendingMembers(): HasMany
    {
        return $this->hasMany(Member::class)->where('status', 'pending');
    }

    /**
     * Get all events hosted by this institution.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get upcoming events for this institution.
     */
    public function upcomingEvents(): HasMany
    {
        return $this->hasMany(Event::class)
            ->where('start_date', '>', now())
            ->where('status', 'published')
            ->orderBy('start_date');
    }

    /**
     * Get all budgets submitted by this institution.
     */
    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    /**
     * Get pending budgets for this institution.
     */
    public function pendingBudgets(): HasMany
    {
        return $this->hasMany(Budget::class)->where('status', 'submitted');
    }

    /**
     * Get approved budgets for this institution.
     */
    public function approvedBudgets(): HasMany
    {
        return $this->hasMany(Budget::class)->where('status', 'approved');
    }

    /**
     * Get all activities related to this institution.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get the user who created this institution.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this institution.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get leaders of this institution.
     */
    public function leaders()
    {
        return $this->hasMany(Member::class)->where('member_type', 'leader');
    }

    /**
     * Get supervisors of this institution.
     */
    public function supervisors()
    {
        return $this->hasMany(Member::class)->where('member_type', 'supervisor');
    }

    /**
     * Check if institution is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if institution is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if institution is approved.
     */
    public function isApproved(): bool
    {
        return in_array($this->status, ['active', 'inactive']);
    }

    /**
     * Get institution type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match ($this->type) {
            'university' => 'University',
            'college' => 'College',
            'institute' => 'Institute',
            'school' => 'School',
            default => 'Institution',
        };
    }

    /**
     * Get institution status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'pending' => 'Pending Approval',
            'suspended' => 'Suspended',
            default => 'Unknown',
        };
    }

    /**
     * Get the logo URL.
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        
        // Generate a placeholder logo
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=000000&background=F9E510&size=200";
    }

    /**
     * Get full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->region,
            $this->postal_code,
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Get member count.
     */
    public function getMemberCountAttribute(): int
    {
        return $this->members()->count();
    }

    /**
     * Get active member count.
     */
    public function getActiveMemberCountAttribute(): int
    {
        return $this->activeMembers()->count();
    }

    /**
     * Get event count.
     */
    public function getEventCountAttribute(): int
    {
        return $this->events()->count();
    }

    /**
     * Get total budget amount for current year.
     */
    public function getTotalBudgetAmountAttribute(): float
    {
        return $this->budgets()
            ->where('financial_year', date('Y'))
            ->where('status', 'approved')
            ->sum('approved_amount') ?? 0;
    }

    /**
     * Scope to filter by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active institutions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get pending institutions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter by region.
     */
    public function scopeRegion($query, string $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope to search institutions.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhere('region', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to order by member count.
     */
    public function scopeOrderByMemberCount($query, string $direction = 'desc')
    {
        return $query->withCount('members')->orderBy('members_count', $direction);
    }
}