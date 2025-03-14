<?php

namespace App\Rules;

use App\Models\Attribute;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAttributeValue implements ValidationRule
{
    protected int $attributeId;

    public function __construct(int $attributeId)
    {
        $this->attributeId = $attributeId;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attributeModel = Attribute::find($this->attributeId);

        if (! $attributeModel) {
            $fail('Invalid attribute ID.');

            return;
        }

        switch ($attributeModel->type) {
            case 'text':
                if (! is_string($value)) {
                    $fail('The value must be a string for text attributes.');
                }
                break;

            case 'date':
                if (! strtotime($value)) {
                    $fail('The value must be a valid date.');
                }
                break;

            case 'number':
                if (! is_numeric($value)) {
                    $fail('The value must be a number.');
                }
                break;

            case 'select':
                $validOptions = json_decode($attributeModel->options, true) ?? [];
                if (! in_array($value, $validOptions)) {
                    $fail('Invalid selection. Allowed values: ' . implode(', ', $validOptions));
                }
                break;
        }
    }
}
