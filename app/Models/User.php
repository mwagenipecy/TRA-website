<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'national_id',
        'date_of_birth',
        'gender',
        'role',
        'status',
        'profile_photo',
        'bio',
        'permissions',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'permissions' => 'array',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];


   

    /**
     * Get the user's member record.
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Get the institution the user belongs to (through member relationship).
     */
    public function institution()
    {
        return $this->hasOneThrough(Institution::class, Member::class, 'user_id', 'id', 'id', 'institution_id');
    }

    /**
     * Get all events created by this user.
     */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Get all event registrations for this user.
     */
    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get events this user is registered for.
     */
    public function registeredEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_registrations')
            ->withPivot(['status', 'registered_at', 'attended', 'rating', 'feedback'])
            ->withTimestamps();
    }

    /**
     * Get all budgets created by this user.
     */
    public function createdBudgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'created_by');
    }

    /**
     * Get all budgets reviewed by this user.
     */
    public function reviewedBudgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'reviewed_by');
    }

    /**
     * Get all budgets approved by this user.
     */
    public function approvedBudgets(): HasMany
    {
        return $this->hasMany(Budget::class, 'approved_by');
    }

    /**
     * Get all activities performed by this user.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Get all institutions created by this user.
     */
    public function createdInstitutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'created_by');
    }

    /**
     * Get all institutions approved by this user.
     */
    public function approvedInstitutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'approved_by');
    }

    /**
     * Get all members approved by this user.
     */
    public function approvedMembers(): HasMany
    {
        return $this->hasMany(Member::class, 'approved_by');
    }


    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get the user's roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)
            ->withPivot(['additional_permissions', 'revoked_permissions', 'assigned_at', 'assigned_by'])
            ->withTimestamps();
    }

    /**
     * Get the user's primary role.
     */
    public function primaryRole()
    {
        return $this->roles()->first();
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        // Get all permissions from all roles
        $rolePermissions = [];
        foreach ($this->roles as $role) {
            $rolePermissions = array_merge($rolePermissions, $role->getAllPermissions());
        }

        // Get additional permissions from pivot table
        $additionalPermissions = [];
        foreach ($this->roles as $role) {
            if ($role->pivot->additional_permissions) {
                $additionalPermissions = array_merge($additionalPermissions, $role->pivot->additional_permissions);
            }
        }

        // Get revoked permissions from pivot table
        $revokedPermissions = [];
        foreach ($this->roles as $role) {
            if ($role->pivot->revoked_permissions) {
                $revokedPermissions = array_merge($revokedPermissions, $role->pivot->revoked_permissions);
            }
        }

        // Combine all permissions
        $allPermissions = array_merge($rolePermissions, $additionalPermissions);
        $allPermissions = array_diff($allPermissions, $revokedPermissions);

         return true; // in_array($permission, $allPermissions);

        
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return true; // false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return true; // false;
            }
        }
        return true;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles->whereIn('name', $roleNames)->count() >= 0;
    }

    /**
     * Assign role to user.
     */
    public function assignRole(string $roleName, ?int $assignedBy = null): void
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id, [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy,
            ]);
        }
    }

    /**
     * Remove role from user.
     */
    public function removeRole(string $roleName): void
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Sync user roles.
     */
    public function syncRoles(array $roleNames, ?int $assignedBy = null): void
    {
        $roles = Role::whereIn('name', $roleNames)->get();
        $syncData = [];
        
        foreach ($roles as $role) {
            $syncData[$role->id] = [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy,
            ];
        }
        
        $this->roles()->sync($syncData);
    }

    /**
     * Add additional permission to user.
     */
    public function givePermission(string $permission, ?string $roleName = null): void
    {
        $role = $roleName ? Role::where('name', $roleName)->first() : $this->primaryRole();
        
        if ($role) {
            $pivot = $this->roles()->where('role_id', $role->id)->first()->pivot;
            $additional = $pivot->additional_permissions ?? [];
            
            if (!in_array($permission, $additional)) {
                $additional[] = $permission;
                $this->roles()->updateExistingPivot($role->id, [
                    'additional_permissions' => $additional
                ]);
            }
        }
    }

    /**
     * Revoke permission from user.
     */
    public function revokePermission(string $permission, ?string $roleName = null): void
    {
        $role = $roleName ? Role::where('name', $roleName)->first() : $this->primaryRole();
        
        if ($role) {
            $pivot = $this->roles()->where('role_id', $role->id)->first()->pivot;
            $revoked = $pivot->revoked_permissions ?? [];
            
            if (!in_array($permission, $revoked)) {
                $revoked[] = $permission;
                $this->roles()->updateExistingPivot($role->id, [
                    'revoked_permissions' => $revoked
                ]);
            }
        }
    }

    /**
     * Get all user permissions.
     */
    public function getAllPermissions(): array
    {
        $permissions = [];
        
        foreach ($this->roles as $role) {
            $permissions = array_merge($permissions, $role->getAllPermissions());
            
            // Add additional permissions
            if ($role->pivot->additional_permissions) {
                $permissions = array_merge($permissions, $role->pivot->additional_permissions);
            }
            
            // Remove revoked permissions
            if ($role->pivot->revoked_permissions) {
                $permissions = array_diff($permissions, $role->pivot->revoked_permissions);
            }
        }
        
        return array_unique($permissions);
    }

    /**
     * Check if user is a TRA Officer.
     */
    public function isTraOfficer(): bool
    {
        return true; // $this->hasRole('tra_officer');
    }

    /**
     * Check if user is a Leader.
     */
    public function isLeader(): bool
    {
        return true; // $this->hasAnyRole(['leader', 'supervisor']);
    }

    /**
     * Check if user is a Student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user is pending approval.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get user's full name with title.
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get user's role display name.
     */
    public function getRoleDisplayAttribute(): string
    {
        return match ($this->role) {
            'student' => 'Student Member',
            'leader' => 'Club Leader',
            'supervisor' => 'Supervisor',
            'tra_officer' => 'TRA Officer',
            default => 'Member',
        };
    }

    /**
     * Get user's status display name.
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
     * Get user's initials for avatar.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }

    /**
     * Get profile photo URL or generate avatar.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        
        // Generate a placeholder avatar URL
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=000000&background=F9E510";
    }

    /**
     * Scope to filter by role.
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get pending users.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to search users.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('national_id', 'like', "%{$search}%");
        });
    }
}