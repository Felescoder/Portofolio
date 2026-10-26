<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = Profile::query()->firstOrFail();

        return response()->json([
            'data' => new ProfileResource($profile),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'short_name' => ['sometimes', 'required', 'string', 'max:50'],
            'role' => ['sometimes', 'required', 'string', 'max:255'],
            'typed_role' => ['sometimes', 'required', 'string', 'max:255'],
            'hero_kicker' => ['sometimes', 'required', 'string', 'max:255'],
            'hero_greeting' => ['sometimes', 'required', 'string', 'max:255'],
            'hero_name_line_1' => ['sometimes', 'required', 'string', 'max:255'],
            'hero_name_line_2' => ['nullable', 'string', 'max:255'],
            'hero_description' => ['sometimes', 'required', 'string'],
            'availability_label' => ['sometimes', 'required', 'string', 'max:255'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'school' => ['nullable', 'string', 'max:255'],
            'major' => ['nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255'],
            'hero_cta_label' => ['sometimes', 'required', 'string', 'max:255'],
            'footer_note' => ['nullable', 'string', 'max:255'],
            'portrait' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $profile = Profile::query()->firstOrFail();
        $portrait = $request->file('portrait');
        unset($data['portrait']);

        if ($portrait) {
            $oldPortrait = $profile->portrait_path;
            $newPortrait = $portrait->store('profile', 'public');

            $data['portrait_path'] = $newPortrait;

            if ($oldPortrait && $oldPortrait !== $newPortrait) {
                Storage::disk('public')->delete($oldPortrait);
            }
        }

        $profile->update($data);

        return response()->json([
            'message' => 'Konten beranda berhasil diperbarui.',
            'data' => new ProfileResource($profile->refresh()),
        ]);
    }
}
