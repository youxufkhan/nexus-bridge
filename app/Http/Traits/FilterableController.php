<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FilterableController
{
    /**
     * Apply filters to the query builder
     */
    protected function applyFilters(Builder $query, Request $request, array $filterConfig): Builder
    {
        foreach ($filterConfig as $filter) {
            $value = $request->input($filter['key']);

            if ($value === null || $value === '') {
                continue;
            }

            match ($filter['type']) {
                    'search' => $this->applySearchFilter($query, $value, $filter['fields']),
                    'select' => $this->applySelectFilter($query, $value, $filter['field']),
                    'date_range' => $this->applyDateRangeFilter($query, $request, $filter['key'], $filter['field']),
                    'number_range' => $this->applyNumberRangeFilter($query, $request, $filter['key'], $filter['field']),
                    'boolean' => $this->applyBooleanFilter($query, $value, $filter['field']),
                    'relation' => $this->applyRelationFilter($query, $value, $filter['relation'], $filter['field']),
                    default => null,
                };
        }

        return $query;
    }

    /**
     * Apply sorting to the query builder
     */
    protected function applySorting(Builder $query, Request $request, array $sortConfig): Builder
    {
        $sortBy = $request->input('sort_by', $sortConfig['default'] ?? 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Validate sort field
        if (!in_array($sortBy, array_column($sortConfig['options'], 'field'))) {
            $sortBy = $sortConfig['default'] ?? 'created_at';
        }

        // Validate direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Check if this is a relation sort
        $sortOption = collect($sortConfig['options'])->firstWhere('field', $sortBy);

        if ($sortOption && is_array($sortOption) && isset($sortOption['relation'])) {
            // Sort by related model
            $relation = $sortOption['relation'];
            $query->join(
                $relation['table'],
                $relation['foreign_key'],
                '=',
                $relation['owner_key']
            )->orderBy($relation['table'] . '.' . $relation['field'], $sortDirection);
        }
        else {
            $query->orderBy($sortBy, $sortDirection);
        }

        return $query;
    }

    /**
     * Apply search filter across multiple fields
     */
    private function applySearchFilter(Builder $query, string $value, array $fields): void
    {
        $query->where(function ($q) use ($value, $fields) {
            foreach ($fields as $field) {
                if (str_contains($field, '.')) {
                    // Relation field
                    [$relation, $relationField] = explode('.', $field);
                    $q->orWhereHas($relation, function ($relationQuery) use ($relationField, $value) {
                                    $relationQuery->where($relationField, 'ILIKE', "%{$value}%");
                                }
                                );
                            }
                            else {
                                $q->orWhere($field, 'ILIKE', "%{$value}%");
                            }
                        }
                    });
    }

    /**
     * Apply select/dropdown filter
     */
    private function applySelectFilter(Builder $query, string $value, string $field): void
    {
        $query->where($field, '=', $value);
    }

    /**
     * Apply date range filter
     */
    private function applyDateRangeFilter(Builder $query, Request $request, string $key, string $field): void
    {
        $from = $request->input($key . '_from');
        $to = $request->input($key . '_to');

        if ($from !== null && $from !== '') {
            $query->whereDate($field, '>=', $from);
        }
        if ($to !== null && $to !== '') {
            $query->whereDate($field, '<=', $to);
        }
    }

    /**
     * Apply number range filter
     */
    private function applyNumberRangeFilter(Builder $query, Request $request, string $key, string $field): void
    {
        $min = $request->input($key . '_min');
        $max = $request->input($key . '_max');

        if ($min !== null && $min !== '') {
            $query->where($field, '>=', $min);
        }
        if ($max !== null && $max !== '') {
            $query->where($field, '<=', $max);
        }
    }

    /**
     * Apply boolean filter
     */
    private function applyBooleanFilter(Builder $query, string $value, string $field): void
    {
        $query->where($field, '=', $value === 'true' || $value === '1');
    }

    /**
     * Apply relation filter
     */
    private function applyRelationFilter(Builder $query, string $value, string $relation, string $field): void
    {
        $query->whereHas($relation, function ($q) use ($field, $value) {
            $q->where($field, '=', $value);
        });
    }

    /**
     * Get active filters count
     */
    protected function getActiveFiltersCount(Request $request, array $filterConfig): int
    {
        $count = 0;
        foreach ($filterConfig as $filter) {
            if ($filter['type'] === 'number_range') {
                $min = $request->input($filter['key'] . '_min');
                $max = $request->input($filter['key'] . '_max');
                if (($min !== null && $min !== '') || ($max !== null && $max !== '')) {
                    $count++;
                }
            }
            else {
                $value = $request->input($filter['key']);
                if ($value !== null && $value !== '') {
                    $count++;
                }
            }
        }

        return $count;
    }
}