<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillGroupResource;
use App\Models\SkillGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SkillGroupController extends Controller
{
    public function index(): JsonResponse
    {
        $groups = SkillGroup::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => SkillGroupResource::collection($groups),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] ??= 0;
        $data['is_active'] ??= true;

        $group = SkillGroup::query()->create($data);

        return response()->json([
            'message' => 'Kelompok keahlian berhasil ditambahkan.',
            'data' => new SkillGroupResource($group),
        ], 201);
    }

    public function update(Request $request, SkillGroup $skillGroup): JsonResponse
    {
        $data = $request->validate($this->rules());

        $skillGroup->update($data);

        return response()->json([
            'message' => 'Kelompok keahlian berhasil diperbarui.',
            'data' => new SkillGroupResource($skillGroup->refresh()),
        ]);
    }

    public function destroy(SkillGroup $skillGroup): JsonResponse
    {
        $skillGroup->delete();

        return response()->json([
            'message' => 'Kelompok keahlian berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'icon' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'items' => ['nullable', 'array'],
            'items.*' => ['string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
