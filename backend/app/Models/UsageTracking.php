<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsageTracking extends Model
{
    protected $table = 'usage_tracking';
    
    protected $fillable = [
        'user_id',
        'subscription_id',
        'feature_name',
        'usage_count',
        'limit_count',
        'tracking_date',
        'metadata',
    ];

    protected $casts = [
        'tracking_date' => 'date',
        'metadata' => 'array',
    ];
}
