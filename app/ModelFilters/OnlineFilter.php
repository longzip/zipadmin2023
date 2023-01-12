<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;

class OnlineFilter extends ModelFilter
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
            return $q->where('mess', 'LIKE', "%$name%")
                ->orWhere('phone', 'LIKE', "%$name%");
        });
    }

    public function sdates($dates)
    {
        $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        $dates[0] = $stardate->startOfDay()->toDateTimeString();
        $dates[1] = $enddate->endOfDay()->toDateTimeString();
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('date', $dates);
        });
    }

    public function chiendich($chiendich)
    {
        return $this->where(function($q) use ($chiendich)
        {
            return $q->where('id_chiendich', $chiendich);
        });
    }

    public function product($product)
    {
        return $this->where(function($q) use ($product)
        {
            return $q->leftJoin('online_line', 'online.id', '=', 'online_line.id_online')->where('product', $product);
        });
    }
}
