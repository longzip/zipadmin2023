<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;

class DaiLyFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    //public $relations = [];

    //->whereBetween('delivery_date',[$ngaygiao,$ngaygiao2])

    public function name($name)
    {
        return $this->where(function($q) use ($name)
        {
            return $q->where('first_name', 'LIKE', "%$name%")
                ->orWhere('last_name', 'LIKE', "%$name%")
                ->orWhere('phone', 'LIKE', "%$name%")
                ->orWhere('address', 'LIKE', "%$name%")
                ->orWhere('city', 'LIKE', "%$name%");
        });
    }

    public function dates($dates)
    {
        $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        $dates[0] = $stardate->startOfDay()->toDateTimeString();
        $dates[1] = $enddate->endOfDay()->toDateTimeString();
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('created_at', $dates);
        });
    }
}


