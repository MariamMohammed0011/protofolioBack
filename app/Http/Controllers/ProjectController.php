<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
public function index()
{
    $projects = Cache::remember('projects', 60, function () {
        return Project::with('categories')->get();
    });

    return ProjectResource::collection($projects);
}
}