<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttribute extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255', 'unique:attributes,name,' . $this->route('attribute')->id],
            'type' => ['sometimes', 'in:text,date,number,select'],
            'options' => 'sometimes|required_if:type,select|array',
            'options.*' => 'string|max:255',
        ];
    }
}
