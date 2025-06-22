<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'category',
        'item_name',
        'description',
        'quantity',
        'unit_cost',
        'total_cost',
        'unit_of_measure',
        'justification',
        'is_approved',
        'approved_amount',
        'approval_notes',
        'priority',
        'is_mandatory',
        'vendors',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'is_approved' => 'boolean',
        'is_mandatory' => 'boolean',
        'vendors' => 'array',
    ];

    /**
     * Get the budget that owns this item
     */
    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Get the priority color for UI display
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            1, 2 => 'red',
            3 => 'yellow',
            4, 5 => 'green',
            default => 'gray'
        };
    }

    /**
     * Get the priority text for UI display
     */
    public function getPriorityTextAttribute()
    {
        return match($this->priority) {
            1 => 'Very High',
            2 => 'High',
            3 => 'Medium',
            4 => 'Low',
            5 => 'Very Low',
            default => 'Unknown'
        };
    }

    /**
     * Calculate total cost based on quantity and unit cost
     */
    public function calculateTotalCost()
    {
        $this->total_cost = $this->quantity * $this->unit_cost;
        return $this->total_cost;
    }

    /**
     * Scope for approved items
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for mandatory items
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Scope for items by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for items by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Get formatted unit cost
     */
    public function getFormattedUnitCostAttribute()
    {
        return 'TZS ' . number_format($this->unit_cost, 2);
    }

    /**
     * Get formatted total cost
     */
    public function getFormattedTotalCostAttribute()
    {
        return 'TZS ' . number_format($this->total_cost, 2);
    }

    /**
     * Get formatted approved amount
     */
    public function getFormattedApprovedAmountAttribute()
    {
        return $this->approved_amount ? 'TZS ' . number_format($this->approved_amount, 2) : null;
    }

    /**
     * Check if item has variance between requested and approved amount
     */
    public function hasVariance()
    {
        return $this->approved_amount && $this->approved_amount != $this->total_cost;
    }

    /**
     * Get variance amount
     */
    public function getVarianceAmount()
    {
        if (!$this->approved_amount) {
            return 0;
        }
        
        return $this->approved_amount - $this->total_cost;
    }

    /**
     * Get variance percentage
     */
    public function getVariancePercentage()
    {
        if (!$this->approved_amount || $this->total_cost == 0) {
            return 0;
        }
        
        return (($this->approved_amount - $this->total_cost) / $this->total_cost) * 100;
    }
}