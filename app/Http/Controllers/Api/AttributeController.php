<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAttribute;
use App\Http\Requests\UpdateAttribute;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AttributeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return AttributeResource::collection(Attribute::filter()->paginate());
    }

    public function store(CreateAttribute $request): JsonResponse
    {
        $request_data = $request->validated();
        $request_data['options'] = $request_data['type'] === 'select' ? $request_data['options'] : null;
        $attribute = Attribute::create($request_data);

        return response()->json([
            'message' => 'Attribute created successfully!',
            'data' => new AttributeResource($attribute),
        ], 201);
    }

    public function show(Attribute $attribute): JsonResponse
    {
        return response()->json([
            'data' => new AttributeResource($attribute),
        ]);
    }

    public function update(UpdateAttribute $request, Attribute $attribute): JsonResponse
    {
        $request_data = $request->validated();

        $request_data['options'] = $request_data['type'] === 'select' ? $request_data['options'] : null;

        $attribute->update($request_data);

        return response()->json([
            'message' => 'Attribute updated successfully!',
            'data' => new AttributeResource($attribute),
        ], 200);
    }

    public function destroy(Attribute $attribute): JsonResponse
    {
        $attribute->delete();

        return response()->json(['message' => 'Attribute deleted successfully'], 200);
    }
}
