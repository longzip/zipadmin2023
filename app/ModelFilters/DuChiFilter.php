<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;
use App\DaChi;
use App\DuChi;

class DuChiFilter extends ModelFilter
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
            return $q->where('bophan', 'LIKE', "%$name%")
            ->orWhere('note', 'LIKE', "%$name%")
                ->orWhere('name', 'LIKE', "%$name%");
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
            return $q->whereBetween('start_chi', $dates)->orwherebetween('end_chi', $dates);
        });

    }

    public function status($status)
    {
        $dachi = 0;
        if ($status == 3) {
            $duchi = DuChi::All();
            foreach ($duchi as $key => $value) {
                $dachi = DaChi::where('id_duchi',$value->id)->sum('money');
                if ((int)$value->amount == round($dachi)) {
                    $arr[] = $value->id; 
                }
            }
            return $this->where(function($q) use ($arr)
            {
                return $q->whereIn('id', $arr)->where('duyet', 2);
            });

        }else{
            if ($status == 2) {
                $duchi = DuChi::All();
                foreach ($duchi as $key => $value) {
                    $dachi = DaChi::where('id_duchi',$value->id)->sum('money');
                    if ((int)$value->amount - round($dachi) > 0) {
                        $arr[] = $value->id; 
                    }
                }
                return $this->where(function($q) use ($arr)
                {
                    return $q->whereIn('id', $arr)->where('duyet', 2);
                });
            }else{
                return $this->where(function($q) use ($status)
                {
                    return $q->where('duyet', $status);
                });
            }
        }

    }
}
