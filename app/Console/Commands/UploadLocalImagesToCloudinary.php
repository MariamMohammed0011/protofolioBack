<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\User; // غيرها حسب موديلك
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UploadLocalImagesToCloudinary extends Command
{
    protected $signature = 'cloudinary:upload-images';
    protected $description = 'Upload local images to Cloudinary and update DB links';

    public function handle()
    {
        $folderPath = public_path('members'); // مكان الصور على جهازك
        $files = File::files($folderPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
            $relativePath = 'members/' . $filename;

            $user = User::where('profile_picture', $relativePath)->first();
            if ($user) {
                $url = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'users_images',
                    'public_id' => $nameWithoutExt
                ])->getSecurePath();

                $user->profile_picture = $url;
                $user->save();

                $this->info("✅ رفعت الصورة وحدثت المشروع: {$user->name}");
            } else {
                $this->warn("❌ ما لقيت مشروع للصورة: $filename");
            }
        }

        $this->info("🎉 كل الصور تم رفعها وتحديثها بقاعدة البيانات!");
    }
}