<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;

class ProjectFilter extends ModelFilter
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
            return $q->where('name_project', 'LIKE', "%$name%")
                ->orWhere('name_project', 'LIKE', "%$name%")
                ->orWhere('sapo_project', 'LIKE', "%$name%");
        });
    }

    public function users($ids)
    {
        return $this->where(function($q) use ($ids)
        {
            return $q->whereIn('user_id', $ids);
        });
    }

    public function dates($dates)
    {
        // $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        // $dates[0] = $enddate->startOfDay()->toDateTimeString();
        $dates[1] = $enddate->endOfDay()->toDateTimeString();

        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('created_at', $dates);
        });
    }

    public function salesareas($ids)
    {
        return $this->where(function($q) use ($ids)
        {
            return $q->whereIn('salesarea_id', $ids);
        });
    }
}
