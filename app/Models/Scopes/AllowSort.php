<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait AllowSort
{
    public function scopeAllowSorts(Builder $query, string $column)
    {
        return $query->orderBy($column);
    } 
}