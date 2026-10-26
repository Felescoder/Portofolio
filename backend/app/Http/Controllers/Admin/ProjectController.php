<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => ProjectResource::collection($projects),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->normalizeTags($request);

        $data = $request->validate($this->rules());
        $data['sort_order'] ??= 0;
        $data['is_active'] ??= true;

        $image = $request->file('image');
        unset($data['image']);

        if ($image) {
            $data = array_merge($data, $this->storeImage($image));
        }

        $project = Project::query()->create($data);

        return response()->json([
            'message' => 'Projek berhasil ditambahkan.',
            'data' => new ProjectResource($project),
        ], 201);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $this->normalizeTags($request);

        $data = $request->validate($this->rules());

        if (array_key_exists('sort_order', $data)) {
            $data['sort_order'] ??= 0;
        }

        $image = $request->file('image');
        unset($data['image']);

        $oldImage = $project->image_path;
        $newImage = null;

        if ($image) {
            $newImage = $this->storeImage($image);
            $data = array_merge($data, $newImage);
        }

        $project->update($data);

        if ($newImage && $oldImage && $oldImage !== $newImage['image_path']) {
            Storage::disk('public')->delete($oldImage);
        }

        return response()->json([
            'message' => 'Projek berhasil diperbarui.',
            'data' => new ProjectResource($project->refresh()),
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        if ($project->image_path) {
            Storage::disk('public')->delete($project->image_path);
        }

        $project->delete();

        return response()->json([
            'message' => 'Projek berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:100'],
            'url' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    private function normalizeTags(Request $request): void
    {
        if (! $request->exists('tags')) {
            return;
        }

        $tags = $request->input('tags');

        if (is_string($tags)) {
            $decoded = json_decode($tags, true);

            $tags = is_array($decoded) && json_last_error() === JSON_ERROR_NONE
                ? $decoded
                : explode(',', $tags);
        }

        if (! is_array($tags)) {
            $tags = [];
        }

        $tags = array_values(array_filter(
            array_map(fn ($tag) => trim((string) $tag), $tags),
            fn (string $tag) => $tag !== '',
        ));

        $request->merge(['tags' => $tags]);
    }

    private function storeImage(UploadedFile $image): array
    {
        $path = $image->store('projects', 'public');
        $dimensions = @getimagesize($image->getRealPath());

        return [
            'image_path' => $path,
            'width' => $dimensions ? (int) $dimensions[0] : null,
            'height' => $dimensions ? (int) $dimensions[1] : null,
        ];
    }
}
