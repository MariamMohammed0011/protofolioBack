<?php

namespace App\Http\Controllers;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

use Illuminate\Http\Request;

class CloudinaryImageController extends Controller
{
    public function uploadAllImages()
{
    $folder = public_path('images');
    $files = scandir($folder);
    $uploadedImages = [];

    foreach ($files as $file) {
        if (!in_array($file, ['.', '..'])) {
            $filePath = $folder . '/' . $file;

            // ارفع الصورة
            $uploaded = Cloudinary::upload($filePath);
            $uploadedImages[] = [
                'file' => $file,
                'url' => $uploaded->getSecurePath()
            ];
        }
    }

    return response()->json([
        'message' => 'All images uploaded',
        'images' => $uploadedImages,
    ]);
}

}
