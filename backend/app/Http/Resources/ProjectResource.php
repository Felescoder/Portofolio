<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'description' => $this->description,
            'image_url' => $this->image_path
                ? url(Storage::disk('public')->url($this->image_path))
                : null,
            'image_alt' => $this->image_alt,
            'width' => $this->width,
            'height' => $this->height,
            'tags' => $this->tags ?? [],
            'url' => $this->url,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
