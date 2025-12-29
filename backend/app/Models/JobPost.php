<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirements',
        'location',
        'job_type',
        'category',
        'experience_level',
        'salary_range',
        'currency',
        'skills',
        'benefits',
        'application_email',
        'application_url',
        'application_deadline',
        'is_featured',
        'is_active',
        'views_count',
        'applications_count',
        'status'
    ];

    protected $casts = [
        'skills' => 'array',
        'benefits' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'application_deadline' => 'date',
        'views_count' => 'integer',
        'applications_count' => 'integer'
    ];

    /**
     * Get the company that posted this job
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class, 'company_id');
    }

    /**
     * Scope for published jobs
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_active', true);
    }

    /**
     * Scope for featured jobs
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for active jobs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by job type
     */
    public function scopeByJobType($query, $jobType)
    {
        return $query->where('job_type', $jobType);
    }

    /**
     * Scope by experience level
     */
    public function scopeByExperienceLevel($query, $level)
    {
        return $query->where('experience_level', $level);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment applications count
     */
    public function incrementApplications()
    {
        $this->increment('applications_count');
    }

    /**
     * Check if job is still accepting applications
     */
    public function isAcceptingApplications(): bool
    {
        if (!$this->is_active || $this->status !== 'published') {
            return false;
        }

        if ($this->application_deadline && $this->application_deadline->isPast()) {
            return false;
        }

        return true;
    }
}
