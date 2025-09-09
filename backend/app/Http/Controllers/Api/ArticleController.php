<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::published()->latest('published_at');

        if ($request->has('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        $articles = $query->paginate(12);

        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles',
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'read_time' => 'nullable|integer|min:1',
            'published' => 'boolean',
        ]);

        $article = Article::create($validated);

        return response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:articles,slug,' . $id,
            'excerpt' => 'sometimes|required|string',
            'content' => 'sometimes|required|string',
            'category' => 'sometimes|required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
            'read_time' => 'nullable|integer|min:1',
            'published' => 'boolean',
        ]);

        $article->update($validated);

        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204);
    }
}
