<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'current_step',
        'score',
        'readiness_title',
        'assessment',
        'recommendations',
        'metadata',
    ];

    protected $casts = [
        'assessment' => 'array',
        'recommendations' => 'array',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


