<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(): JsonResponse
    {
        $experiences = Experience::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => ExperienceResource::collection($experiences),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] ??= 0;
        $data['is_active'] ??= true;

        $experience = Experience::query()->create($data);

        return response()->json([
            'message' => 'Pengalaman berhasil ditambahkan.',
            'data' => new ExperienceResource($experience),
        ], 201);
    }

    public function update(Request $request, Experience $experience): JsonResponse
    {
        $data = $request->validate($this->rules());

        $experience->update($data);

        return response()->json([
            'message' => 'Pengalaman berhasil diperbarui.',
            'data' => new ExperienceResource($experience->refresh()),
        ]);
    }

    public function destroy(Experience $experience): JsonResponse
    {
        $experience->delete();

        return response()->json([
            'message' => 'Pengalaman berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'period' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
