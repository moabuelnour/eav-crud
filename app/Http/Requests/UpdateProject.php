<?php

namespace App\Http\Requests;

use App\Rules\ValidAttributeValue;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProject extends FormRequest
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
            'name' => 'sometimes|string|max:255|unique:projects,name,' . $this->project->id,
            'status' => 'sometimes|in:active,inactive',

            'attributes.*.attribute_id' => 'sometimes|integer|exists:attributes,id',
            'attributes.*.value' => [
                'sometimes',
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

            'users' => 'nullable|array',
            'users.*' => 'exists:users,id',

            'timesheets' => 'nullable|array',
            'timesheets.*' => 'exists:timesheets,id',
        ];
    }
}
