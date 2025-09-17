<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'permissions',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role_assignments', 'role_id', 'user_id')
                    ->withPivot(['assigned_at', 'assigned_by'])
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function hasPermission($permission): bool
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function assignToUser($userId, $assignedBy = null): void
    {
        $this->users()->attach($userId, [
            'assigned_at' => now(),
            'assigned_by' => $assignedBy,
        ]);
    }

    public function removeFromUser($userId): void
    {
        $this->users()->detach($userId);
    }
}