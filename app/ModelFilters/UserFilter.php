<?php namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
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
            return $q->where('username', 'LIKE', "%$name%")
                ->orWhere('name', 'LIKE', "%$name%")
                ->orWhere('email', 'LIKE', "%$name%");
        });
    }

    public function dates($dates)
    {
        return $this->where(function($q) use ($dates)
        {
            return $q->whereDate('date_off','>',$dates[0]);
        });
    }
}
