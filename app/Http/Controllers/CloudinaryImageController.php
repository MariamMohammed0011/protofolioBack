<?php

namespace App\Http\Controllers;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CloudinaryImageController extends Controller
{
public function uploadAllImagesFromPublic()
{
    $folder = public_path('images'); // المسار المحلي
    $files = scandir($folder);       // قراءة كل الملفات

    $uploadedImages = [];

    foreach ($files as $file) {
        if (!in_array($file, ['.', '..'])) {
            $filePath = $folder . '/' . $file;

            // افتح الملف كـ SplFileObject
            $fileObject = new \Illuminate\Http\File($filePath);

            // ارفع الصورة إلى Cloudinary
            $cloudinaryPath = Storage::putFile('projects', $fileObject);

            // اجلب الرابط المباشر من Cloudinary
            $secureUrl = Cloudinary::getUrl($cloudinaryPath);

            // أضفها للنتيجة
            $uploadedImages[] = [
                'file' => $file,
                'cloudinary_path' => $cloudinaryPath,
                'url' => $secureUrl,
            ];
        }
    }

    return response()->json([
        'message' => 'All public/images uploaded to Cloudinary!',
        'images' => $uploadedImages,
    ]);
}
}