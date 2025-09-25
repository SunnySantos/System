<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Searchable
{
    public static function search(Request $request, ?Builder $query = null): Builder
    {
        if (!$query) {
            $query = self::query();
        }

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $columns = defined('static::SEARCHABLE_COLUMNS') ? static::SEARCHABLE_COLUMNS : ['id'];

                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        // Sort
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = defined('static::ALLOWED_SORTS') ? static::ALLOWED_SORTS : ['id'];
        $allowedDirections = defined('static::ALLOWED_SORT_DIRECTIONS') ? static::ALLOWED_SORT_DIRECTIONS : ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

        if (!in_array($direction, $allowedDirections)) {
            $direction = 'desc';
        }

        $query->orderBy($sort, $direction);

        return $query;
    }
}
