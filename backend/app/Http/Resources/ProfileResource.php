<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'role' => $this->role,
            'typed_role' => $this->typed_role,
            'hero_kicker' => $this->hero_kicker,
            'hero_greeting' => $this->hero_greeting,
            'hero_name_line_1' => $this->hero_name_line_1,
            'hero_name_line_2' => $this->hero_name_line_2,
            'hero_description' => $this->hero_description,
            'availability_label' => $this->availability_label,
            'location' => $this->location,
            'school' => $this->school,
            'major' => $this->major,
            'email' => $this->email,
            'portrait_url' => $this->portrait_path
                ? url(Storage::disk('public')->url($this->portrait_path))
                : null,
            'hero_cta_label' => $this->hero_cta_label,
            'footer_note' => $this->footer_note,
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
