<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'period' => $this->period,
            'icon' => $this->icon,
            'role' => $this->role,
            'company' => $this->company,
            'description' => $this->description,
            'tags' => $this->tags ?? [],
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    }
}
