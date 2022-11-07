<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait AllowFilterSearch
{
    public function scopeAllowFilters(Builder $query, ...$keys)
    {
        foreach ($keys as $key) {
            if($value = request()->query($key)) {
                $query->where($key, $value);
            }
        }
        return $query;
    }
    
    public function scopeAllowSearch(Builder $query, ...$keys)
    {
        if($search = request()->query('search')) {
            foreach ($keys as $index => $key) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $query->{$method}($key, "LIKE", "%{$search}%");
            }
        }
        return $query;
    }

    public function scopeAllowTrash(Builder $query) 
    {
        if(request()->query('trash')) {
            $query->onlyTrashed();
        }
        return $query;
    }
}