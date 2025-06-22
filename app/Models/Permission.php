<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'category',
        'is_system_permission',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_system_permission' => 'boolean',
    ];

    /**
     * Scope to filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get system permissions.
     */
    public function scopeSystem($query)
    {
        return $query->where('is_system_permission', true);
    }

    /**
     * Scope to get custom permissions.
     */
    public function scopeCustom($query)
    {
        return $query->where('is_system_permission', false);
    }

    /**
     * Get permissions grouped by category.
     */
    public static function getGroupedPermissions(): array
    {
        return self::all()->groupBy('category')->map(function ($permissions) {
            return $permissions->map(function ($permission) {
                return [
                    'name' => $permission->name,
                    'display_name' => $permission->display_name,
                    'description' => $permission->description,
                ];
            });
        })->toArray();
    }
}