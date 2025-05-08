<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate uploaded file
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('document');
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();

        // Store to local 'public' disk
        $localPath = $file->storeAs('uploads', $fileName, 'public');

        // Store to MinIO
        $minioUploaded = Storage::disk('minio')->putFileAs('uploads', $file, $fileName);
        $minioPath = $minioUploaded ? 'uploads/' . $fileName : false;

        return response()->json([
            'local_path' => $localPath ? 'storage/' . $localPath : false,
            'minio_path' => $minioPath,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $image = $request->file('image');
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();

        // Store original to 'public' disk
        $originalPath = $image->storeAs('uploads', $fileName, 'public');

        // Create thumbnail using Intervention Image
        $thumbnailPath = 'thumbnails/' . $fileName;
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);

        Image::make($image->getRealPath())
            ->fit(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($thumbnailFullPath);

        return response()->json([
            'original' => 'storage/uploads/' . $fileName,
            'thumbnail' => 'storage/' . $thumbnailPath,
        ]);
    }
}