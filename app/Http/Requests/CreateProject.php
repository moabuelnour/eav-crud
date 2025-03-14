<?php

namespace App\Http\Requests;

use App\Rules\ValidAttributeValue;
use Illuminate\Foundation\Http\FormRequest;

class CreateProject extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:projects,name',
            'status' => 'required|in:active,inactive',
            'users' => 'nullable|array',
            'users.*' => 'sometimes|integer|exists:users,id',
            'attributes.*.attribute_id' => 'required|integer|exists:attributes,id',
            'attributes.*.value' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Extract the index from attributes.*.value (e.g., "attributes.0.value")
                    preg_match('/attributes\.(\d+)\.value/', $attribute, $matches);
                    $index = $matches[1] ?? null;

                    if ($index === null) {
                        return $fail('Invalid attribute index.');
                    }

                    $attributeId = request()->input("attributes.$index.attribute_id");

                    if (! $attributeId) {
                        return $fail('Attribute ID is required.');
                    }

                    // Apply the ValidAttributeValue rule dynamically
                    (new ValidAttributeValue((int) $attributeId))->validate($attribute, $value, $fail);
                },
            ],

            'timesheets' => 'nullable|array',
            'timesheets.*' => 'exists:timesheets,id',
        ];
    }
}
