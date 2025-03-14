<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $query)
    {
        $filters = request('filters', []);
        if (! empty($filters)) {
            $filterClass = self::resolveFilterClass();

            if (class_exists($filterClass)) {
                (new $filterClass($query, $filters))->apply();
            }
        }

        return $query;
    }

    protected static function resolveFilterClass(): string
    {
        $filterClass = 'App\\Filters\\' . class_basename(static::class) . 'Filter';

        return class_exists($filterClass) ? $filterClass : 'App\\Filters\\QueryFilter';
    }

    public static function queryWithFilters()
    {
        return static::query()->filter();
    }
}
