<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadController extends Controller
{
    /**
     * Upload a single file
     */
    public function uploadFile(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240', // 10MB max
                'type' => 'required|in:image,document,video,audio,other',
                'category' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:500',
                'tags' => 'array',
                'is_public' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('file');
            $type = $request->type;
            $category = $request->category ?? 'general';
            $isPublic = $request->get('is_public', true);

            // Validate file type based on category
            $allowedTypes = $this->getAllowedFileTypes($type);
            if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File type not allowed for this category'
                ], 422);
            }

            // Generate unique filename
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            
            // Determine storage path
            $storagePath = $this->getStoragePath($type, $category);
            $fullPath = $storagePath . '/' . $filename;

            // Store file
            $storedPath = $file->storeAs($storagePath, $filename, 'public');

            // Get file info
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();

            // Process image if it's an image
            $thumbnailPath = null;
            if ($type === 'image') {
                $thumbnailPath = $this->generateThumbnail($storedPath, $filename);
            }

            // Create database record
            $mediaFile = MediaFile::create([
                'user_id' => auth()->id(),
                'original_name' => $originalName,
                'filename' => $filename,
                'file_path' => $storedPath,
                'thumbnail_path' => $thumbnailPath,
                'file_type' => $type,
                'category' => $category,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'description' => $request->description,
                'tags' => $request->tags ?? [],
                'is_public' => $isPublic,
                'upload_ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => $mediaFile->id,
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'file_path' => $storedPath,
                    'thumbnail_path' => $thumbnailPath,
                    'file_type' => $type,
                    'category' => $category,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'url' => Storage::url($storedPath),
                    'thumbnail_url' => $thumbnailPath ? Storage::url($thumbnailPath) : null,
                    'uploaded_at' => $mediaFile->created_at
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload multiple files
     */
    public function uploadMultiple(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array|max:10',
                'files.*' => 'required|file|max:10240',
                'type' => 'required|in:image,document,video,audio,other',
                'category' => 'nullable|string|max:100',
                'description' => 'nullable|string|max:500',
                'tags' => 'array',
                'is_public' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $files = $request->file('files');
            $type = $request->type;
            $category = $request->category ?? 'general';
            $isPublic = $request->get('is_public', true);
            $uploadedFiles = [];
            $failedFiles = [];

            foreach ($files as $index => $file) {
                try {
                    // Validate file type
                    $allowedTypes = $this->getAllowedFileTypes($type);
                    if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
                        $failedFiles[] = [
                            'index' => $index,
                            'filename' => $file->getClientOriginalName(),
                            'error' => 'File type not allowed'
                        ];
                        continue;
                    }

                    // Generate unique filename
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = Str::uuid() . '.' . $extension;
                    
                    // Determine storage path
                    $storagePath = $this->getStoragePath($type, $category);
                    
                    // Store file
                    $storedPath = $file->storeAs($storagePath, $filename, 'public');

                    // Get file info
                    $fileSize = $file->getSize();
                    $mimeType = $file->getMimeType();

                    // Process image if it's an image
                    $thumbnailPath = null;
                    if ($type === 'image') {
                        $thumbnailPath = $this->generateThumbnail($storedPath, $filename);
                    }

                    // Create database record
                    $mediaFile = MediaFile::create([
                        'user_id' => auth()->id(),
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'file_path' => $storedPath,
                        'thumbnail_path' => $thumbnailPath,
                        'file_type' => $type,
                        'category' => $category,
                        'mime_type' => $mimeType,
                        'file_size' => $fileSize,
                        'description' => $request->description,
                        'tags' => $request->tags ?? [],
                        'is_public' => $isPublic,
                        'upload_ip' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]);

                    $uploadedFiles[] = [
                        'id' => $mediaFile->id,
                        'original_name' => $originalName,
                        'filename' => $filename,
                        'file_path' => $storedPath,
                        'thumbnail_path' => $thumbnailPath,
                        'file_type' => $type,
                        'category' => $category,
                        'file_size' => $fileSize,
                        'mime_type' => $mimeType,
                        'url' => Storage::url($storedPath),
                        'thumbnail_url' => $thumbnailPath ? Storage::url($thumbnailPath) : null,
                        'uploaded_at' => $mediaFile->created_at
                    ];

                } catch (\Exception $e) {
                    $failedFiles[] = [
                        'index' => $index,
                        'filename' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Files processed',
                'data' => [
                    'uploaded' => $uploadedFiles,
                    'failed' => $failedFiles,
                    'summary' => [
                        'total' => count($files),
                        'uploaded' => count($uploadedFiles),
                        'failed' => count($failedFiles)
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's files
     */
    public function getUserFiles(Request $request): JsonResponse
    {
        try {
            $query = MediaFile::where('user_id', auth()->id());

            // Filter by type
            if ($request->has('type')) {
                $query->where('file_type', $request->type);
            }

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('original_name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%")
                      ->orWhere('tags', 'LIKE', "%{$search}%");
                });
            }

            $files = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 20));

            // Add URLs to each file
            $files->getCollection()->transform(function ($file) {
                $file->url = Storage::url($file->file_path);
                $file->thumbnail_url = $file->thumbnail_path ? Storage::url($file->thumbnail_path) : null;
                return $file;
            });

            return response()->json([
                'success' => true,
                'data' => $files->items(),
                'pagination' => [
                    'current_page' => $files->currentPage(),
                    'last_page' => $files->lastPage(),
                    'per_page' => $files->perPage(),
                    'total' => $files->total(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a file
     */
    public function deleteFile($id): JsonResponse
    {
        try {
            $file = MediaFile::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Delete file from storage
            if (Storage::disk('public')->exists($file->file_path)) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Delete thumbnail if exists
            if ($file->thumbnail_path && Storage::disk('public')->exists($file->thumbnail_path)) {
                Storage::disk('public')->delete($file->thumbnail_path);
            }

            // Delete database record
            $file->delete();

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
     * Get file info
     */
    public function getFileInfo($id): JsonResponse
    {
        try {
            $file = MediaFile::where('id', $id)
                ->where(function ($query) {
                    $query->where('user_id', auth()->id())
                          ->orWhere('is_public', true);
                })
                ->firstOrFail();

            $file->url = Storage::url($file->file_path);
            $file->thumbnail_url = $file->thumbnail_path ? Storage::url($file->thumbnail_path) : null;

            return response()->json([
                'success' => true,
                'data' => $file
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'File not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get allowed file types for each category
     */
    private function getAllowedFileTypes(string $type): array
    {
        return match ($type) {
            'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            'document' => ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'],
            'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
            'audio' => ['mp3', 'wav', 'ogg', 'aac', 'flac'],
            'other' => ['zip', 'rar', '7z', 'tar', 'gz'],
            default => []
        };
    }

    /**
     * Get storage path based on type and category
     */
    private function getStoragePath(string $type, string $category): string
    {
        $year = date('Y');
        $month = date('m');
        
        return "uploads/{$type}/{$category}/{$year}/{$month}";
    }

    /**
     * Generate thumbnail for image
     */
    private function generateThumbnail(string $filePath, string $filename): ?string
    {
        try {
            $fullPath = Storage::disk('public')->path($filePath);
            
            if (!file_exists($fullPath)) {
                return null;
            }

            // Create thumbnail
            $thumbnailFilename = 'thumb_' . $filename;
            $thumbnailPath = dirname($filePath) . '/thumbnails/' . $thumbnailFilename;
            $thumbnailFullPath = Storage::disk('public')->path($thumbnailPath);

            // Ensure thumbnail directory exists
            $thumbnailDir = dirname($thumbnailFullPath);
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Generate thumbnail using Intervention Image
            $image = Image::make($fullPath);
            $image->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image->save($thumbnailFullPath);

            return $thumbnailPath;

        } catch (\Exception $e) {
            \Log::error('Failed to generate thumbnail: ' . $e->getMessage());
            return null;
        }
    }
}