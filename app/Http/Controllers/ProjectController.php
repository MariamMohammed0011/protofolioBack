<?php
use Illuminate\Support\Facades\Cache;

public function index()
{
    $projects = Cache::remember('projects', 60, function () {
        return Project::with('categories')->get();
    });

    return ProjectResource::collection($projects);
}
