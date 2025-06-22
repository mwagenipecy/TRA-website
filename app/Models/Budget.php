<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'budget_code',
        'institution_id',
        'created_by',
        'type',
        'description',
        'objectives',
        'total_amount',
        'financial_year',
        'start_date',
        'end_date',
        'status',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
        'review_comments',
        'rejection_reason',
        'revision_notes',
        'approved_amount',
        'spent_amount',
        'remaining_amount',
        'attachments',
        'is_recurring',
        'recurrence_pattern',
        'priority_level',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'objectives' => 'array',
        'total_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'revision_notes' => 'array',
        'attachments' => 'array',
        'is_recurring' => 'boolean',
        'priority_level' => 'integer',
        'financial_year' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($budget) {
            if (empty($budget->budget_code)) {
                $budget->budget_code = self::generateBudgetCode();
            }
            
            if (empty($budget->financial_year)) {
                $budget->financial_year = date('Y');
            }
        });

        static::updating(function ($budget) {
            // Update remaining amount when spent amount changes
            if ($budget->isDirty('spent_amount') && $budget->approved_amount) {
                $budget->remaining_amount = $budget->approved_amount - $budget->spent_amount;
            }
        });
    }

    /**
     * Generate unique budget code.
     */
    private static function generateBudgetCode(): string
    {
        $year = date('Y');
        $month = date('m');
        $lastBudget = self::whereYear('created_at', $year)->latest('id')->first();
        $sequence = $lastBudget ? $lastBudget->id + 1 : 1;
        
        return "BDG-{$year}{$month}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the institution that owns the budget.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the user who created the budget.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who reviewed the budget.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the user who approved the budget.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all budget items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    /**
     * Get approved budget items.
     */
    public function approvedItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class)->where('is_approved', true);
    }

    /**
     * Check if budget is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if budget is submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if budget is under review.
     */
    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    /**
     * Check if budget is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if budget is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if budget needs revision.
     */
    public function needsRevision(): bool
    {
        return $this->status === 'revision_required';
    }

    /**
     * Check if budget is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->end_date < now();
    }

    /**
     * Get budget type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match ($this->type) {
            'yearly' => 'Annual Budget',
            'event' => 'Event Budget',
            'project' => 'Project Budget',
            'emergency' => 'Emergency Budget',
            'equipment' => 'Equipment Budget',
            default => 'Budget',
        };
    }

    /**
     * Get budget status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'revision_required' => 'Revision Required',
            'expired' => 'Expired',
            default => 'Unknown',
        };
    }

    /**
     * Get priority level display name.
     */
    public function getPriorityDisplayAttribute(): string
    {
        return match ($this->priority_level) {
            1 => 'Very Low',
            2 => 'Low',
            3 => 'Normal',
            4 => 'High',
            5 => 'Critical',
            default => 'Normal',
        };
    }

    /**
     * Get budget utilization percentage.
     */
    public function getUtilizationPercentageAttribute(): float
    {
        if (!$this->approved_amount || $this->approved_amount == 0) {
            return 0;
        }

        return round(($this->spent_amount / $this->approved_amount) * 100, 1);
    }

    /**
     * Get days remaining until budget expires.
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->end_date < now()) {
            return 0;
        }

        return now()->diffInDays($this->end_date);
    }

    /**
     * Submit budget for review.
     */
    public function submit(): void
    {
        $this->update(['status' => 'submitted']);
        
        Activity::log(
            'budget_submitted',
            "Budget '{$this->title}' was submitted for review",
            $this->creator,
            $this,
            $this->institution
        );
    }

    /**
     * Start review process.
     */
    public function startReview(?User $reviewer = null): void
    {
        $this->update([
            'status' => 'under_review',
            'reviewed_by' => $reviewer?->id ?? auth()->id(),
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Approve budget.
     */
    public function approve(?User $approver = null, ?float $approvedAmount = null, ?string $comments = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approver?->id ?? auth()->id(),
            'approved_at' => now(),
            'approved_amount' => $approvedAmount ?? $this->total_amount,
            'remaining_amount' => ($approvedAmount ?? $this->total_amount) - $this->spent_amount,
            'review_comments' => $comments,
        ]);

        Activity::log(
            'budget_approved',
            "Budget '{$this->title}' was approved",
            $approver ?? auth()->user(),
            $this,
            $this->institution
        );
    }

    /**
     * Reject budget.
     */
    public function reject(?User $reviewer = null, ?string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer?->id ?? auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);

        Activity::log(
            'budget_rejected',
            "Budget '{$this->title}' was rejected",
            $reviewer ?? auth()->user(),
            $this,
            $this->institution
        );
    }

    /**
     * Request revision.
     */
    public function requestRevision(?User $reviewer = null, array $notes = []): void
    {
        $this->update([
            'status' => 'revision_required',
            'reviewed_by' => $reviewer?->id ?? auth()->id(),
            'reviewed_at' => now(),
            'revision_notes' => $notes,
        ]);
    }

    /**
     * Record expense.
     */
    public function recordExpense(float $amount, string $description = ''): void
    {
        if (!$this->isApproved()) {
            throw new \Exception('Cannot record expenses for non-approved budget');
        }

        $newSpentAmount = $this->spent_amount + $amount;
        
        if ($newSpentAmount > $this->approved_amount) {
            throw new \Exception('Expense exceeds approved budget amount');
        }

        $this->update([
            'spent_amount' => $newSpentAmount,
            'remaining_amount' => $this->approved_amount - $newSpentAmount,
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
     * Scope to get pending budgets.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope to get approved budgets.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to filter by type.
     */
    public function scopeType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by financial year.
     */
    public function scopeFinancialYear($query, int $year)
    {
        return $query->where('financial_year', $year);
    }

    /**
     * Scope to filter by institution.
     */
    public function scopeInstitution($query, int $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    /**
     * Scope to search budgets.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('budget_code', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}