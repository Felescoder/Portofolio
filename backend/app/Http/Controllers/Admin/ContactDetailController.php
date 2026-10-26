<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactDetailResource;
use App\Models\ContactDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactDetailController extends Controller
{
    public function index(): JsonResponse
    {
        $details = ContactDetail::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => ContactDetailResource::collection($details),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());
        $data['sort_order'] ??= 0;

        $detail = ContactDetail::query()->create($data);

        return response()->json([
            'message' => 'Detail kontak berhasil ditambahkan.',
            'data' => new ContactDetailResource($detail),
        ], 201);
    }

    public function update(Request $request, ContactDetail $contactDetail): JsonResponse
    {
        $data = $request->validate($this->rules());
        $contactDetail->update($data);

        return response()->json([
            'message' => 'Detail kontak berhasil diperbarui.',
            'data' => new ContactDetailResource($contactDetail->refresh()),
        ]);
    }

    public function destroy(ContactDetail $contactDetail): JsonResponse
    {
        $contactDetail->delete();

        return response()->json([
            'message' => 'Detail kontak berhasil dihapus.',
        ]);
    }

    private function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:1024'],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
