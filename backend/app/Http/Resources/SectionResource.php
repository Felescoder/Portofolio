<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'kicker' => $this->kicker,
            'title' => $this->title,
            'highlight' => $this->highlight,
            'description' => $this->description,
            'meta' => $this->meta,
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
