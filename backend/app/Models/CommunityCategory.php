<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunityCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_active',
        'rules',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rules' => 'array',
        'metadata' => 'array'
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(CommunityPost::class, 'category_id');
    }
}
