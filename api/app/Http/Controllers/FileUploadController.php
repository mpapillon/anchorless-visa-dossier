<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileUploadRequest;
use App\Http\Resources\FileUploadResource;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * GET /api/file-uploads
     */
    public function index()
    {
        $files = FileUpload::query()
            ->latest()
            ->get()
            ->groupBy('type')
            ->map(fn ($group) => FileUploadResource::collection($group));

        return response()->json($files);
    }

    /**
     * POST /api/file-uploads
     */
    public function store(StoreFileUploadRequest $request)
    {
        $validated = $request->validated();

        $file = $request->file('file');
        $path = $file->store('upload');

        $fileUpload = FileUpload::create([
            'type' => $validated['type'],
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return $fileUpload->toResource()->response()->setStatusCode(201);
    }

    /**
     * GET /api/file-uploads/{fileUpload}
     */
    public function show(FileUpload $fileUpload)
    {
        return $fileUpload->toResource();
    }

    /**
     * DELETE /api/file-uploads/{fileUpload}
     */
    public function destroy(FileUpload $fileUpload)
    {
        Storage::delete($fileUpload->file_path);
        $fileUpload->delete();

        return response()->noContent();
    }

    /**
     * GET /api/file-uploads/{fileUpload}/download
     */
    public function download(FileUpload $fileUpload)
    {
        return Storage::download($fileUpload->file_path, $fileUpload->original_name);
    }
}
