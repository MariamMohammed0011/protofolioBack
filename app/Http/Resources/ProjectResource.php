<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'projectTitle' => $this->project_title,
            'imgPath' => $this->img_path,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
