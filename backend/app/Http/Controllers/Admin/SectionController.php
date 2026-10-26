<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function show(string $key): JsonResponse
    {
        $section = Section::query()->where('key', $key)->firstOrFail();

        return response()->json([
            'data' => new SectionResource($section),
        ]);
    }

    public function update(Request $request, string $key): JsonResponse
    {
        $section = Section::query()->where('key', $key)->firstOrFail();

        $data = $request->validate([
            'kicker' => ['nullable', 'string', 'max:255'],
            'title' => ['sometimes', 'required', 'string', 'max:2048'],
            'highlight' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string'],
            'meta' => ['nullable', 'array'],
        ]);

        $section->update($data);

        return response()->json([
            'message' => 'Judul dan deskripsi seksi berhasil diperbarui.',
            'data' => new SectionResource($section->refresh()),
        ]);
    }
}
