<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CheTaiFilter extends ModelFilter
{
    public $relations = [];

    public function name($name)
    {
        return $this->where(function($q) use ($name)
        {
            return $q->where('name', 'LIKE', "%$name%");
        });
    }


}