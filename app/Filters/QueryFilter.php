<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class QueryFilter
{
    protected Builder $query;

    protected array $filters;

    /**
     * Create a new class instance.
     */
    public function __construct(Builder $query, array $filters)
    {
        $this->query = $query;
        $this->filters = $filters;
    }

    public function apply(): Builder
    {
        foreach ($this->filters as $field => $value) {
            if (method_exists($this, $field)) {
                $this->{$field}($value);
            } else {
                $this->applyFilter($field, $value);
            }
        }

        return $this->query;
    }

    protected function applyFilter(string $field, mixed $value): void
    {
        if (is_array($value)) {
            foreach ($value as $operator => $filterValue) {
                if (in_array($operator, ['=', '>', '<'])) {
                    $this->query->where($field, $operator, $filterValue);
                } elseif ($operator === 'LIKE') {
                    $this->query->where($field, 'LIKE', "%{$filterValue}%");
                }
            }
        } else {
            $this->query->where($field, $value);
        }
    }
}
