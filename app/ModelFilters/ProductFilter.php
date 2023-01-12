<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;

class ProductFilter extends ModelFilter
{
    public function name($name)
    {
        return $this->where(function($q) use ($name)
        {
            return $q->where('code', 'LIKE', "%$name%")
                ->orWhere('name', 'LIKE', "%$name%");
        });
    }

    public function p($p)
    {
        return $this->where(function($q) use ($p)
        {
            return $q->where('groups',$p);
        });
    }
}


