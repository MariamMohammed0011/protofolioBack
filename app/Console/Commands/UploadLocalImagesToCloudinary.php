<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Project; // غيرها حسب موديلك
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UploadLocalImagesToCloudinary extends Command
{
    protected $signature = 'cloudinary:upload-images';
    protected $description = 'Upload local images to Cloudinary and update DB links';

    public function handle()
    {
        $folderPath = public_path('images'); // مكان الصور على جهازك
        $files = File::files($folderPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
            $relativePath = 'images/' . $filename;

            $project = Project::where('img_path', $relativePath)->first();
            if ($project) {
                $url = Cloudinary::upload($file->getRealPath(), [
                    'folder' => 'projects_images',
                    'public_id' => $nameWithoutExt
                ])->getSecurePath();

                $project->img_path = $url;
                $project->save();

                $this->info("✅ رفعت الصورة وحدثت المشروع: {$project->title}");
            } else {
                $this->warn("❌ ما لقيت مشروع للصورة: $filename");
            }
        }

        $this->info("🎉 كل الصور تم رفعها وتحديثها بقاعدة البيانات!");
    }
}