<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
        'is_system_role',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'permissions' => 'array',
        'is_system_role' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that belong to this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['additional_permissions', 'revoked_permissions', 'assigned_at', 'assigned_by'])
            ->withTimestamps();
    }

    /**
     * Check if role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? []);
    }

    /**
     * Add permission to role.
     */
    public function addPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }
    }

    /**
     * Remove permission from role.
     */
    public function removePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions = array_filter($permissions, fn($p) => $p !== $permission);
        $this->update(['permissions' => array_values($permissions)]);
    }

    /**
     * Sync permissions for this role.
     */
    public function syncPermissions(array $permissions): void
    {
        $this->update(['permissions' => $permissions]);
    }

    /**
     * Get all permissions for this role including system permissions.
     */
    public function getAllPermissions(): array
    {
        $permissions = $this->permissions ?? [];
        
        // TRA Officers get all permissions automatically
        if ($this->name === 'tra_officer') {
            $allPermissions = Permission::pluck('name')->toArray();
            return array_unique(array_merge($permissions, $allPermissions));
        }
        
        return $permissions;
    }

    /**
     * Scope to get active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get system roles.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system_role', true);
    }

    /**
     * Scope to get non-system roles.
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system_role', false);
    }
}