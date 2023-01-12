<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class CSVCFilter extends ModelFilter
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
            return $q->where('sanpham', 'LIKE', "%$name%")
                ->orWhere('showroom', 'LIKE', "%$name%");
        });
    }

    public function sdates($sdates)
    {
        return $this->where(function($q) use ($sdates)
        {
            return $q->whereDate('date','<', $sdates[0]);
        });
    }
}
