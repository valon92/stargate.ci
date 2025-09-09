<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = FAQ::active()->ordered()->get();

        return response()->json($faqs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        $faq = FAQ::create($validated);

        return response()->json($faq, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $faq = FAQ::active()->findOrFail($id);

        return response()->json($faq);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $faq = FAQ::findOrFail($id);

        $validated = $request->validate([
            'question' => 'sometimes|required|string',
            'answer' => 'sometimes|required|string',
            'order' => 'nullable|integer|min:0',
            'active' => 'boolean',
        ]);

        $faq->update($validated);

        return response()->json($faq);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        return response()->json(null, 204);
    }
}
