<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use App\ThiCong;

class ThiCongFilter extends ModelFilter
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
                ->orWhere('so_number', 'LIKE', "%$name%");
        });
    }

    public function dates($dates)
    {
        return $this->where(function($q) use ($dates)
        {
             return $q->whereBetween('delivery_date', $dates)->orderBy('delivery_date','asc');
        });
    }

    public function thicong($thicong)
    {
        if ($thicong == 1) {
            $tc = array();
            $all = ThiCong::where('status',1)->pluck('thicong','id');
            foreach ($all as $key => $value) {
                foreach (json_decode($value) as $v) {
                    if (\Auth::user()->id == $v) {
                        $tc[] = $key;
                    }
                }   
            }
            return $this->where(function($q) use ($tc)
            {
                return $q->whereIn('id', $tc);
            });
        }
    }
}
