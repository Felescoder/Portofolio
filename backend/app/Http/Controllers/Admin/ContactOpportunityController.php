<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactOpportunityResource;
use App\Models\ContactOpportunity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactOpportunityController extends Controller
{
    public function index(): JsonResponse
    {
        $opportunities = ContactOpportunity::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => ContactOpportunityResource::collection($opportunities),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] ??= 0;

        $opportunity = ContactOpportunity::query()->create($data);

        return response()->json([
            'message' => 'Peluang berhasil ditambahkan.',
            'data' => new ContactOpportunityResource($opportunity),
        ], 201);
    }

    public function update(Request $request, ContactOpportunity $contactOpportunity): JsonResponse
    {
        $data = $request->validate($this->rules());
        $contactOpportunity->update($data);

        return response()->json([
            'message' => 'Peluang berhasil diperbarui.',
            'data' => new ContactOpportunityResource($contactOpportunity->refresh()),
        ]);
    }

    public function destroy(ContactOpportunity $contactOpportunity): JsonResponse
    {
        $contactOpportunity->delete();

        return response()->json([
            'message' => 'Peluang berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'icon' => ['nullable', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
