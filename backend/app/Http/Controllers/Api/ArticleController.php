<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ContentCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Get all articles
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Article::query();

            // Filter by category if provided
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Filter by published status
            if ($request->has('published')) {
                $query->where('published', $request->published);
            } else {
                $query->where('published', true);
            }

            // Search functionality
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('excerpt', 'LIKE', "%{$search}%")
                      ->orWhere('content', 'LIKE', "%{$search}%");
                });
            }

            // Filter by author
            if ($request->has('author')) {
                $query->where('author', $request->author);
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'published_at');
            $sortOrder = $request->get('sort_order', 'desc');
            
            $allowedSortFields = ['published_at', 'created_at', 'updated_at', 'title'];
            if (in_array($sortBy, $allowedSortFields)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            $articles = $query->paginate($request->get('per_page', 15));

            return response()->json([
                'success' => true,
                'data' => $articles->items(),
                'pagination' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific article
     */
    public function show($id): JsonResponse
    {
        try {
            $article = Article::with(['category', 'author'])
                ->where('id', $id)
                ->orWhere('slug', $id)
                ->firstOrFail();

            // Increment view count
            $article->increment('view_count');

            return response()->json([
                'success' => true,
                'data' => $article
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new article (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'excerpt' => 'required|string|max:500',
                'content' => 'required|string',
                'category_id' => 'required|exists:content_categories,id',
                'featured_image' => 'nullable|string',
                'tags' => 'array',
                'status' => 'in:draft,published,archived',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $slug = $request->slug ?? Str::slug($request->title);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Article::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $article = Article::create([
                'title' => $request->title,
                'slug' => $slug,
                'excerpt' => $request->excerpt,
                'content' => $request->content,
                'category_id' => $request->category_id,
                'author_id' => auth()->id(),
                'featured_image' => $request->featured_image,
                'tags' => $request->tags ?? [],
                'status' => $request->status ?? 'draft',
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'published_at' => $request->status === 'published' ? now() : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article created successfully',
                'data' => $article->load(['category', 'author'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an article (Admin only)
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|string|max:255',
                'excerpt' => 'sometimes|string|max:500',
                'content' => 'sometimes|string',
                'category_id' => 'sometimes|exists:content_categories,id',
                'featured_image' => 'nullable|string',
                'tags' => 'sometimes|array',
                'status' => 'sometimes|in:draft,published,archived',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only([
                'title', 'excerpt', 'content', 'category_id', 
                'featured_image', 'tags', 'status', 'meta_title', 'meta_description'
            ]);

            // Handle slug update
            if ($request->has('title') && $request->title !== $article->title) {
                $slug = Str::slug($request->title);
                $originalSlug = $slug;
                $counter = 1;
                while (Article::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $updateData['slug'] = $slug;
            }

            // Handle publish date
            if ($request->has('status') && $request->status === 'published' && $article->status !== 'published') {
                $updateData['published_at'] = now();
            }

            $article->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully',
                'data' => $article->load(['category', 'author'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an article (Admin only)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get article categories
     */
    public function getCategories(): JsonResponse
    {
        try {
            $categories = ContentCategory::where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'description']);

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular articles
     */
    public function getPopular(): JsonResponse
    {
        try {
            $articles = Article::with(['category', 'author'])
                ->where('status', 'published')
                ->orderBy('view_count', 'desc')
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $articles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch popular articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get related articles
     */
    public function getRelated($id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            
            $relatedArticles = Article::with(['category', 'author'])
                ->where('status', 'published')
                ->where('id', '!=', $id)
                ->where(function ($query) use ($article) {
                    $query->where('category_id', $article->category_id)
                          ->orWhereJsonContains('tags', $article->tags);
                })
                ->orderBy('published_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $relatedArticles
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch related articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Like/Unlike an article
     */
    public function toggleLike($id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            $user = auth()->user();

            // Check if user already liked this article
            $existingLike = DB::table('article_likes')
                ->where('article_id', $id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingLike) {
                // Unlike
                DB::table('article_likes')
                    ->where('article_id', $id)
                    ->where('user_id', $user->id)
                    ->delete();
                
                $article->decrement('like_count');
                $liked = false;
            } else {
                // Like
                DB::table('article_likes')->insert([
                    'article_id' => $id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $article->increment('like_count');
                $liked = true;
            }

            return response()->json([
                'success' => true,
                'message' => $liked ? 'Article liked' : 'Article unliked',
                'data' => [
                    'liked' => $liked,
                    'like_count' => $article->fresh()->like_count
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle like',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}