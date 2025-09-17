<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchIndex extends Model
{
    protected $table = 'search_index';
    
    protected $fillable = [
        'searchable_type',
        'searchable_id',
        'title',
        'content',
        'excerpt',
        'keywords',
        'tags',
        'category',
        'author',
        'status',
        'relevance_score',
        'view_count',
        'indexed_at',
    ];

    protected $casts = [
        'keywords' => 'array',
        'tags' => 'array',
        'indexed_at' => 'datetime',
    ];
}
