<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'subscriber_id',
        'name',
        'slug',
        'description',
        'category',
        'icon',
        'documentation_url',
        'github_url',
        'demo_url',
        'features',
        'is_new',
        'is_popular',
        'downloads_count',
        'stars_count',
        'users_count',
        'status'
    ];

    protected $casts = [
        'features' => 'array',
        'is_new' => 'boolean',
        'is_popular' => 'boolean',
        'downloads_count' => 'integer',
        'stars_count' => 'integer',
        'users_count' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
                
                // Ensure unique slug
                $originalSlug = $product->slug;
                $counter = 1;
                while (static::where('slug', $product->slug)->exists()) {
                    $product->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }
}
