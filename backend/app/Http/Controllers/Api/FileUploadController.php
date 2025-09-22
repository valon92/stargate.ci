<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\MediaFile;

class FileUploadController extends Controller
{
    /**
     * Upload a single file
     */
    public function uploadFile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'type' => 'required|in:image,video,document,audio',
            'context' => 'sometimes|string|max:255', // e.g., 'post', 'comment', 'profile'
            'context_id' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $type = $request->type;
            
            // Validate file type based on upload type
            $allowedMimes = $this->getAllowedMimes($type);
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid file type for the specified upload type',
                    'allowed_types' => $allowedMimes
                ], 422);
            }

            // Generate unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Store file in appropriate directory
            $directory = "uploads/{$type}s/" . date('Y/m');
            $path = $file->storeAs($directory, $filename, 'public');

            // Create database record
            $mediaFile = MediaFile::create([
                'user_id' => $request->user()->id,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'url' => Storage::url($path),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'type' => $type,
                'context' => $request->context,
                'context_id' => $request->context_id,
                'metadata' => [
                    'width' => null,
                    'height' => null,
                    'duration' => null,
                ]
            ]);

            // Generate thumbnails for images
            if ($type === 'image') {
                $this->generateThumbnails($mediaFile);
            }

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => $mediaFile->id,
                    'filename' => $mediaFile->filename,
                    'original_name' => $mediaFile->original_name,
                    'url' => $mediaFile->url,
                    'type' => $mediaFile->type,
                    'size' => $mediaFile->size,
                    'mime_type' => $mediaFile->mime_type,
                    'uploaded_at' => $mediaFile->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array|max:10',
            'files.*' => 'file|max:10240',
            'type' => 'required|in:image,video,document,audio',
            'context' => 'sometimes|string|max:255',
            'context_id' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $uploadedFiles = [];
        $errors = [];

        foreach ($request->file('files') as $index => $file) {
            try {
                // Create a temporary request for single file upload
                $tempRequest = new Request();
                $tempRequest->files->set('file', $file);
                $tempRequest->merge([
                    'type' => $request->type,
                    'context' => $request->context,
                    'context_id' => $request->context_id,
                ]);
                $tempRequest->setUserResolver($request->getUserResolver());

                $response = $this->uploadFile($tempRequest);
                $responseData = json_decode($response->getContent(), true);

                if ($responseData['success']) {
                    $uploadedFiles[] = $responseData['data'];
                } else {
                    $errors[] = [
                        'index' => $index,
                        'filename' => $file->getClientOriginalName(),
                        'error' => $responseData['message']
                    ];
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'index' => $index,
                    'filename' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];
            }
        }

        return response()->json([
            'success' => count($uploadedFiles) > 0,
            'message' => count($uploadedFiles) . ' files uploaded successfully',
            'data' => [
                'uploaded' => $uploadedFiles,
                'errors' => $errors,
                'total_uploaded' => count($uploadedFiles),
                'total_errors' => count($errors)
            ]
        ], count($uploadedFiles) > 0 ? 201 : 422);
    }

    /**
     * Get user's uploaded files
     */
    public function getUserFiles(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|in:image,video,document,audio',
            'context' => 'sometimes|string',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = MediaFile::where('user_id', $request->user()->id);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('context')) {
            $query->where('context', $request->context);
        }

        $perPage = $request->get('per_page', 20);
        $files = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }

    /**
     * Delete a file
     */
    public function deleteFile(Request $request, $id): JsonResponse
    {
        $mediaFile = MediaFile::findOrFail($id);

        // Check if user owns the file or is admin
        if ($mediaFile->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($mediaFile->path)) {
                Storage::disk('public')->delete($mediaFile->path);
            }

            // Delete thumbnails if they exist
            if ($mediaFile->type === 'image' && $mediaFile->metadata) {
                $thumbnails = $mediaFile->metadata['thumbnails'] ?? [];
                foreach ($thumbnails as $thumbnail) {
                    if (Storage::disk('public')->exists($thumbnail['path'])) {
                        Storage::disk('public')->delete($thumbnail['path']);
                    }
                }
            }

            // Delete database record
            $mediaFile->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get allowed MIME types for upload type
     */
    private function getAllowedMimes(string $type): array
    {
        return match ($type) {
            'image' => [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'image/svg+xml'
            ],
            'video' => [
                'video/mp4',
                'video/mpeg',
                'video/quicktime',
                'video/webm',
                'video/x-msvideo'
            ],
            'audio' => [
                'audio/mpeg',
                'audio/wav',
                'audio/ogg',
                'audio/mp4',
                'audio/webm'
            ],
            'document' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain',
                'text/csv'
            ],
            default => []
        };
    }

    /**
     * Generate thumbnails for images
     */
    private function generateThumbnails(MediaFile $mediaFile): void
    {
        // This is a placeholder for thumbnail generation
        // In production, you would use packages like Intervention Image
        // to generate thumbnails of different sizes
        
        $thumbnails = [
            'small' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 300, 'height' => 300],
            'large' => ['width' => 800, 'height' => 600],
        ];

        $generatedThumbnails = [];
        
        foreach ($thumbnails as $size => $dimensions) {
            // Placeholder - in real implementation, generate actual thumbnails
            $generatedThumbnails[$size] = [
                'url' => $mediaFile->url, // Would be thumbnail URL
                'path' => $mediaFile->path, // Would be thumbnail path
                'width' => $dimensions['width'],
                'height' => $dimensions['height']
            ];
        }

        // Update metadata with thumbnail information
        $metadata = $mediaFile->metadata ?? [];
        $metadata['thumbnails'] = $generatedThumbnails;
        
        $mediaFile->update(['metadata' => $metadata]);
    }
}