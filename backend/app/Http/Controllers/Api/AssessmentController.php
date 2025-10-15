<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_step' => 'integer|min:0|max:4',
            'score' => 'integer|min:0|max:100',
            'readiness_title' => 'nullable|string|max:255',
            'assessment' => 'required|array',
            'recommendations' => 'nullable|array',
            'metadata' => 'nullable|array',
        ]);

        $result = AssessmentResult::create([
            'user_id' => $request->user()?->id,
            'session_id' => $request->header('X-Session-Id') ?? $request->session()->getId(),
            'current_step' => $validated['current_step'] ?? 0,
            'score' => $validated['score'] ?? 0,
            'readiness_title' => $validated['readiness_title'] ?? null,
            'assessment' => $validated['assessment'],
            'recommendations' => $validated['recommendations'] ?? null,
            'metadata' => $validated['metadata'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Assessment saved',
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $query = AssessmentResult::query();

        if ($request->user()) {
            $query->where('user_id', $request->user()->id);
        } elseif ($sid = $request->header('X-Session-Id')) {
            $query->where('session_id', $sid);
        }

        $items = $query->orderByDesc('created_at')->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $result = AssessmentResult::findOrFail($id);
        // Simple authorization: allow owner or session match
        $sid = $request->header('X-Session-Id');
        if ($request->user()?->id !== $result->user_id && ($sid && $sid !== $result->session_id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        return response()->json(['success' => true, 'data' => $result]);
    }
}


