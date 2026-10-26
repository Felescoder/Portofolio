<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialLinkResource;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index(): JsonResponse
    {
        $links = SocialLink::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => SocialLinkResource::collection($links),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] ??= 0;
        $data['is_active'] ??= true;

        $link = SocialLink::query()->create($data);

        return response()->json([
            'message' => 'Saluran kontak berhasil ditambahkan.',
            'data' => new SocialLinkResource($link),
        ], 201);
    }

    public function update(Request $request, SocialLink $socialLink): JsonResponse
    {
        $data = $request->validate($this->rules());

        $socialLink->update($data);

        return response()->json([
            'message' => 'Saluran kontak berhasil diperbarui.',
            'data' => new SocialLinkResource($socialLink->refresh()),
        ]);
    }

    public function destroy(SocialLink $socialLink): JsonResponse
    {
        $socialLink->delete();

        return response()->json([
            'message' => 'Saluran kontak berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'href' => ['required', 'string', 'max:2048'],
            'icon' => ['nullable', 'string', 'max:255'],
            'aria_label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
