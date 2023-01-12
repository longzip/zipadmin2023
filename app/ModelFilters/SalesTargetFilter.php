<?php namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;

class SalesTargetFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function dates($dates)
    {
        // $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        // $dates[0] = $enddate->startOfDay()->toDateTimeString();
        $dates[1] = $enddate->endOfDay()->toDateTimeString();

        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('close', $dates);
        });
    }
}
