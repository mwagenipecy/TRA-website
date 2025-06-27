<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'institution_id',
        'student_id',
        'course_of_study',
        'year_of_study',
        'member_type',
        'status',
        'joined_date',
        'graduation_date',
        'interests',
        'skills',
        'motivation',
        'approved_by',
        'approved_at',
        'approval_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'joined_date' => 'date',
        'graduation_date' => 'date',
        'interests' => 'array',
        'skills' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the member.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the institution that the member belongs to.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Get the user who approved this member.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if member is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if member is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if member is a leader.
     */
    public function isLeader(): bool
    {
        return $this->member_type === 'leader';
    }

    /**
     * Check if member is a supervisor.
     */
    public function isSupervisor(): bool
    {
        return $this->member_type === 'supervisor';
    }

    /**
     * Get member type display name.
     */
    public function getMemberTypeDisplayAttribute(): string
    {
        return match ($this->member_type) {
            'student' => 'Student Member',
            'leader' => 'Club Leader',
            'supervisor' => 'Supervisor',
            default => 'Member',
        };
    }

    /**
     * Get member status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'pending' => 'Pending Approval',
            'graduated' => 'Graduated',
            'suspended' => 'Suspended',
            default => 'Unknown',
        };
    }


    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    /**
     * Get academic year display.
     */
    public function getAcademicYearDisplayAttribute(): string
    {
        if (!$this->year_of_study) {
            return 'N/A';
        }

        return match ($this->year_of_study) {
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
            5 => '5th Year',
            6 => '6th Year',
            default => $this->year_of_study . 'th Year',
        };
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active members.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get pending members.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter by member type.
     */
    public function scopeMemberType($query, string $type)
    {
        return $query->where('member_type', $type);
    }

    /**
     * Scope to get leaders.
     */
    public function scopeLeaders($query)
    {
        return $query->where('member_type', 'leader');
    }

    /**
     * Scope to get supervisors.
     */
    public function scopeSupervisors($query)
    {
        return $query->where('member_type', 'supervisor');
    }

    /**
     * Scope to filter by institution.
     */
    public function scopeInstitution($query, int $institutionId)
    {
        return $query->where('institution_id', $institutionId);
    }

    /**
     * Scope to filter by year of study.
     */
    public function scopeYearOfStudy($query, int $year)
    {
        return $query->where('year_of_study', $year);
    }


    public static function getManagedMembers(User $user)
    {
        if ($user->role === 'tra_officer') {
            return static::with(['user', 'institution']);
        }

        // Get institutions where user is a leader/supervisor
        $institutionIds = $user->members()
                             ->whereIn('member_type', ['leader', 'supervisor'])
                             ->where('status', 'active')
                             ->pluck('institution_id');

        return static::with(['user', 'institution'])
                    ->whereIn('institution_id', $institutionIds);
    }



    public function canBeApprovedBy($user){


     

        if($user->role=='admin'){


            return true;

        }else{

            return false;
        }


    }
    
}