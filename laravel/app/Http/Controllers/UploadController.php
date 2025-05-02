<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $image = $request->file('image');
        $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('public/uploads', $fileName);

        // Create thumbnail
        $thumbnailPath = 'public/thumbnails/' . $fileName;
        $thumbnail = Image::make($image->getRealPath());
        $thumbnail->fit(200, 200, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/' . $thumbnailPath));

        return back()->with('success', 'Image uploaded and thumbnail created!');
    }
}
