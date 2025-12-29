<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class JobPostController extends Controller
{
    /**
     * Get all job posts
     */
    public function index(Request $request)
    {
        try {
            $query = JobPost::with('company')
                ->published()
                ->orderBy('is_featured', 'desc')
                ->orderBy('created_at', 'desc');

            // Filter by category
            if ($request->has('category') && $request->category) {
                $query->where('category', $request->category);
            }

            // Filter by job type
            if ($request->has('job_type') && $request->job_type) {
                $query->where('job_type', $request->job_type);
            }

            // Filter by experience level
            if ($request->has('experience_level') && $request->experience_level) {
                $query->where('experience_level', $request->experience_level);
            }

            // Filter by location
            if ($request->has('location') && $request->location) {
                $query->where('location', 'like', '%' . $request->location . '%');
            }

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('requirements', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $jobs = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $jobs->items(),
                'pagination' => [
                    'current_page' => $jobs->currentPage(),
                    'last_page' => $jobs->lastPage(),
                    'per_page' => $jobs->perPage(),
                    'total' => $jobs->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load job posts',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single job post
     */
    public function show(Request $request, string $id)
    {
        try {
            $job = JobPost::with('company')
                ->published()
                ->findOrFail($id);

            // Increment views
            DB::table('job_posts')->where('id', $job->id)->increment('views_count');
            $job->refresh();

            return response()->json([
                'success' => true,
                'data' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job post not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new job post
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:30',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|string|in:full-time,part-time,contract,internship',
            'category' => 'required|string|in:software,robotics,ai,data-science,machine-learning,automation,iot',
            'experience_level' => 'nullable|string|in:entry,mid,senior,executive',
            'salary_range' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:3',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'benefits' => 'nullable|array',
            'benefits.*' => 'string|max:255',
            'application_email' => 'nullable|email',
            'application_url' => 'nullable|url',
            'application_deadline' => 'nullable|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $company = Subscriber::where('email', $user->email)->first();
            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company profile not found'
                ], 404);
            }

            $job = JobPost::create([
                'company_id' => $company->id,
                'title' => $request->title,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'location' => $request->location,
                'job_type' => $request->job_type,
                'category' => $request->category,
                'experience_level' => $request->experience_level,
                'salary_range' => $request->salary_range,
                'currency' => $request->currency ?? 'USD',
                'skills' => $request->skills ?? [],
                'benefits' => $request->benefits ?? [],
                'application_email' => $request->application_email,
                'application_url' => $request->application_url,
                'application_deadline' => $request->application_deadline,
                'status' => 'published'
            ]);

            $job->load('company');

            return response()->json([
                'success' => true,
                'message' => 'Job post created successfully',
                'data' => $job
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create job post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a job post
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|min:30',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'sometimes|string|in:full-time,part-time,contract,internship',
            'category' => 'sometimes|string|in:software,robotics,ai,data-science,machine-learning,automation,iot',
            'experience_level' => 'nullable|string|in:entry,mid,senior,executive',
            'salary_range' => 'nullable|string|max:100',
            'currency' => 'nullable|string|max:3',
            'skills' => 'nullable|array',
            'benefits' => 'nullable|array',
            'application_email' => 'nullable|email',
            'application_url' => 'nullable|url',
            'application_deadline' => 'nullable|date|after_or_equal:today',
            'status' => 'sometimes|string|in:published,draft,closed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $company = Subscriber::where('email', $user->email)->first();
            $job = JobPost::findOrFail($id);

            // Check if user owns the job post
            if ($job->company_id !== $company->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $job->update($request->only([
                'title', 'description', 'requirements', 'location', 'job_type',
                'category', 'experience_level', 'salary_range', 'currency',
                'skills', 'benefits', 'application_email', 'application_url',
                'application_deadline', 'status'
            ]));

            $job->load('company');

            return response()->json([
                'success' => true,
                'message' => 'Job post updated successfully',
                'data' => $job
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a job post
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required'
                ], 401);
            }

            $company = Subscriber::where('email', $user->email)->first();
            $job = JobPost::findOrFail($id);

            // Check if user owns the job post
            if ($job->company_id !== $company->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $job->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job post deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete job post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get job categories
     */
    public function categories()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 'software', 'name' => 'Software Development', 'icon' => '💻'],
                ['id' => 'robotics', 'name' => 'Robotics', 'icon' => '🤖'],
                ['id' => 'ai', 'name' => 'Artificial Intelligence', 'icon' => '🧠'],
                ['id' => 'data-science', 'name' => 'Data Science', 'icon' => '📊'],
                ['id' => 'machine-learning', 'name' => 'Machine Learning', 'icon' => '🔬'],
                ['id' => 'automation', 'name' => 'Automation', 'icon' => '⚙️'],
                ['id' => 'iot', 'name' => 'IoT', 'icon' => '📡']
            ]
        ]);
    }

    /**
     * Get job types
     */
    public function jobTypes()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 'full-time', 'name' => 'Full Time'],
                ['id' => 'part-time', 'name' => 'Part Time'],
                ['id' => 'contract', 'name' => 'Contract'],
                ['id' => 'internship', 'name' => 'Internship']
            ]
        ]);
    }

    /**
     * Get experience levels
     */
    public function experienceLevels()
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['id' => 'entry', 'name' => 'Entry Level'],
                ['id' => 'mid', 'name' => 'Mid Level'],
                ['id' => 'senior', 'name' => 'Senior Level'],
                ['id' => 'executive', 'name' => 'Executive']
            ]
        ]);
    }
}
