<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Get all products
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with('subscriber')
                ->where('status', 'published')
                ->orderBy('is_popular', 'desc')
                ->orderBy('is_new', 'desc')
                ->orderBy('created_at', 'desc');

            // Filter by category
            if ($request->has('category') && $request->category && $request->category !== 'all') {
                $query->where('category', $request->category);
            }

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by user products
            if ($request->has('my_products') && $request->my_products === 'true' && $request->user()) {
                $subscriber = Subscriber::where('email', $request->user()->email)->first();
                if ($subscriber) {
                    $query->where('subscriber_id', $subscriber->id);
                }
            }

            $perPage = $request->get('per_page', 15);
            $products = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $products->items(),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single product
     */
    public function show(Request $request, string $id)
    {
        try {
            $product = Product::with('subscriber')
                ->where('status', 'published')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Create a new product
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required|string|in:api,tools,sdk,cloud,documentation,libraries',
            'icon' => 'nullable|string|max:10',
            'documentation_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'demo_url' => 'nullable|url|max:500',
            'features' => 'nullable|array',
            'features.*' => 'string|max:200',
            'status' => 'nullable|string|in:draft,published'
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
                    'message' => 'Unauthorized'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $product = Product::create([
                'subscriber_id' => $subscriber->id,
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'icon' => $request->icon ?? '🚀',
                'documentation_url' => $request->documentation_url,
                'github_url' => $request->github_url,
                'demo_url' => $request->demo_url,
                'features' => $request->features ?? [],
                'status' => $request->status ?? 'published'
            ]);

            $product->load('subscriber');

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a product
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|min:20',
            'category' => 'sometimes|string|in:api,tools,sdk,cloud,documentation,libraries',
            'icon' => 'nullable|string|max:10',
            'documentation_url' => 'nullable|url|max:500',
            'github_url' => 'nullable|url|max:500',
            'demo_url' => 'nullable|url|max:500',
            'features' => 'nullable|array',
            'features.*' => 'string|max:200',
            'status' => 'nullable|string|in:draft,published,archived'
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
                    'message' => 'Unauthorized'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $product = Product::findOrFail($id);

            // Check ownership
            if ($product->subscriber_id !== $subscriber->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this product'
                ], 403);
            }

            $product->update($request->only([
                'name', 'description', 'category', 'icon',
                'documentation_url', 'github_url', 'demo_url',
                'features', 'status'
            ]));

            $product->load('subscriber');

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a product
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscriber not found'
                ], 404);
            }

            $product = Product::findOrFail($id);

            // Check ownership
            if ($product->subscriber_id !== $subscriber->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this product'
                ], 403);
            }

            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's products
     */
    public function myProducts(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $subscriber = Subscriber::where('email', $user->email)->first();
            if (!$subscriber) {
                return response()->json([
                    'success' => true,
                    'data' => []
                ]);
            }

            $products = Product::with('subscriber')
                ->where('subscriber_id', $subscriber->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load your products',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}