<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\ContentCategory;
use App\Models\Tutorial;
use App\Models\Video;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    /**
     * Get all tutorials
     */
    public function getTutorials(Request $request): JsonResponse
    {
        $query = Tutorial::with(['category', 'author'])
            ->where('status', 'published');

        // Apply filters
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $tutorials = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $tutorials
        ]);
    }

    /**
     * Get all articles
     */
    public function getArticles(Request $request): JsonResponse
    {
        $query = ContentItem::with(['category', 'author'])
            ->where('type', 'article')
            ->where('status', 'published');

        // Apply filters
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $articles = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $articles
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = ContentCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get specific content item
     */
    public function show($id): JsonResponse
    {
        $content = ContentItem::with(['category', 'author', 'tags'])
            ->findOrFail($id);

        // Increment view count
        $content->increment('view_count');

        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }

    /**
     * Create tutorial (authenticated)
     */
    public function createTutorial(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:content_categories,id',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'estimated_duration' => 'required|integer|min:1',
            'tags' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $tutorial = Tutorial::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'difficulty_level' => $request->difficulty_level,
            'estimated_duration' => $request->estimated_duration,
            'author_id' => $request->user()->id,
            'status' => 'draft',
            'tags' => $request->tags ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tutorial created successfully',
            'data' => $tutorial
        ], 201);
    }

    /**
     * Update tutorial (authenticated)
     */
    public function updateTutorial(Request $request, $id): JsonResponse
    {
        $tutorial = Tutorial::findOrFail($id);

        // Check if user can edit this tutorial
        if ($tutorial->author_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'content' => 'sometimes|string',
            'category_id' => 'sometimes|exists:content_categories,id',
            'difficulty_level' => 'sometimes|integer|min:1|max:5',
            'estimated_duration' => 'sometimes|integer|min:1',
            'tags' => 'sometimes|array',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $tutorial->update($request->only([
            'title', 'description', 'content', 'category_id',
            'difficulty_level', 'estimated_duration', 'tags', 'status'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Tutorial updated successfully',
            'data' => $tutorial
        ]);
    }

    /**
     * Delete tutorial (authenticated)
     */
    public function deleteTutorial(Request $request, $id): JsonResponse
    {
        $tutorial = Tutorial::findOrFail($id);

        // Check if user can delete this tutorial
        if ($tutorial->author_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $tutorial->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tutorial deleted successfully'
        ]);
    }

    /**
     * Complete tutorial (authenticated)
     */
    public function completeTutorial(Request $request, $id): JsonResponse
    {
        $tutorial = Tutorial::findOrFail($id);

        // Here you would typically create a completion record
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Tutorial marked as completed'
        ]);
    }

    /**
     * Bookmark tutorial (authenticated)
     */
    public function bookmarkTutorial(Request $request, $id): JsonResponse
    {
        $tutorial = Tutorial::findOrFail($id);

        // Here you would typically create a bookmark record
        // For now, we'll just return success

        return response()->json([
            'success' => true,
            'message' => 'Tutorial bookmarked'
        ]);
    }

    /**
     * Get all content (admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $query = ContentItem::with(['category', 'author']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $content = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $content
        ]);
    }

    /**
     * Store content (admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,tutorial,video,quiz',
            'content' => 'required|string',
            'category_id' => 'required|exists:content_categories,id',
            'status' => 'required|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $content = ContentItem::create([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'author_id' => $request->user()->id,
            'status' => $request->status,
            'excerpt' => $request->excerpt ?? substr(strip_tags($request->content), 0, 200),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Content created successfully',
            'data' => $content
        ], 201);
    }

    /**
     * Update content (admin only)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $content = ContentItem::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'category_id' => 'sometimes|exists:content_categories,id',
            'status' => 'sometimes|in:draft,published,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $content->update($request->only(['title', 'content', 'category_id', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Content updated successfully',
            'data' => $content
        ]);
    }

    /**
     * Delete content (admin only)
     */
    public function destroy($id): JsonResponse
    {
        $content = ContentItem::findOrFail($id);
        $content->delete();

        return response()->json([
            'success' => true,
            'message' => 'Content deleted successfully'
        ]);
    }

    /**
     * Publish content (admin only)
     */
    public function publish(Request $request, $id): JsonResponse
    {
        $content = ContentItem::findOrFail($id);
        $content->update(['status' => 'published']);

        return response()->json([
            'success' => true,
            'message' => 'Content published successfully'
        ]);
    }
}