<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => ServiceResource::collection($services),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] ??= 0;
        $data['is_active'] ??= true;

        $service = Service::query()->create($data);

        return response()->json([
            'message' => 'Layanan berhasil ditambahkan.',
            'data' => new ServiceResource($service),
        ], 201);
    }

    public function update(Request $request, Service $service): JsonResponse
    {
        $data = $request->validate($this->rules());

        $service->update($data);

        return response()->json([
            'message' => 'Layanan berhasil diperbarui.',
            'data' => new ServiceResource($service->refresh()),
        ]);
    }

    public function destroy(Service $service): JsonResponse
    {
        $service->delete();

        return response()->json([
            'message' => 'Layanan berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'number' => ['nullable', 'string', 'max:10'],
            'icon' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
