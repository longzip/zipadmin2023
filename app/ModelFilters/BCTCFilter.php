<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class BCTCFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name($name)
    {
        return $this->where(function($q) use ($name)
        {
            return $q->where('name', 'LIKE', "%$name%")
                ->orWhere('code', 'LIKE', "%$name%");
        });
    }

    public function dates($dates)
    {
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('created_at', $dates);
        });
    }
}
