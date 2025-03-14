<?php

namespace App\Filters;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder;

class ProjectFilter extends QueryFilter
{
    protected function applyFilter(string $field, mixed $value): void
    {
        if (in_array($field, ['name', 'status'])) {
            parent::applyFilter($field, $value);

            return;
        }

        $attribute = Attribute::where('name', $field)->first();
        if (! $attribute) {
            return;
        }

        $this->query->whereHas('attributes', function (Builder $query) use ($attribute, $value) {
            $query->where('attribute_id', $attribute->id);

            if (is_array($value)) {
                foreach ($value as $operator => $filterValue) {
                    $this->applyEavFilter($query, $attribute->type, $operator, $filterValue);
                }
            } else {
                $query->where('value', $value);
            }
        });
    }

    private function applyEavFilter(Builder $query, string $type, string $operator, mixed $value): void
    {
        if (! in_array($operator, ['=', '>', '<', 'LIKE'])) {
            return;
        }

        switch ($type) {
            case 'number':
                $query->whereRaw("CAST(value AS DECIMAL) $operator ?", [$value]);
                break;
            case 'date':
                $query->whereRaw("STR_TO_DATE(value, '%Y-%m-%d') $operator ?", [$value]);
                break;
            case 'text':
            case 'select':
            default:
                if ($operator === 'LIKE') {
                    $query->where('value', 'LIKE', "%{$value}%");
                } else {
                    $query->where('value', $operator, $value);
                }
        }
    }
}
