<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $table = 'search_history';
    
    protected $fillable = [
        'user_id',
        'session_id',
        'query',
        'search_type',
        'filters',
        'results_count',
        'searched_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'searched_at' => 'datetime',
    ];
}
