<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;
use NoiThatZip\Contact\Models\Contact;
use App\DiemRoi;

class ContactFilter extends ModelFilter
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
                ->orWhere('city', 'LIKE', "%$name%")
                ->orWhere('zalo', 'LIKE', "%$name%");
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
        $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        $dates[0] = $stardate->startOfDay()->toDateTimeString();
        $dates[1] = $enddate->endOfDay()->toDateTimeString();
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('created_at', $dates);
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
            return $q->whereBetween('start_date', $dates);
        });
    }
    public function khuvucs($khuvucs){
        $all = Contact::whereNotNull('salesarea_id')->pluck('salesarea_id','id');
        foreach ($khuvucs as $i) {
            // $ias = $i;
            foreach ($all as $key => $value) {
                foreach (json_decode($value) as $k => $v) {
                    if ((integer) $i == $v) {
                        $khuvucs[] = $key;
                    }
                }   
            }
        }
        // dd(json_encode($id));
        return $this->where(function($q) use ($khuvucs)
        {
            return $q->whereIn('id', $khuvucs);
        // dd($q);
        });
    }
    public function salesareas($ids)
    {
        $all = Contact::whereNotNull('salesarea_id')->pluck('salesarea_id','id');
        foreach ($ids as $i) {
            // $ias = $i;
            foreach ($all as $key => $value) {
                foreach (json_decode($value) as $k => $v) {
                    if ((integer) $i == $v) {
                        $id[] = $key;
                    }
                }   
            }
        }
        // dd(json_encode($id));
        return $this->where(function($q) use ($id)
        {
            return $q->whereIn('id', $id);
        });
    }

    public function costcenter($ids)
    {
        $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->whereIn('costcenter_id',$ids)->pluck('model_id');
        
        return $this->where(function($q) use ($id)
        {
            return $q->whereIn('id', $id);
        });
    }
    public function status($status)
    {
        return $this->where(function($q) use ($status)
        {
            return $q->where('status', $status);
        });
    }

    public function p($p)
    {
        // dd($p);
        $list_products = \DB::table('products')->where('groups',$p)->pluck('id');
        if($list_products->isNotEmpty()){
            $list = $list_products;
            $list_custom = \DB::table('product_lines')->where('productable_type','NoiThatZip\Contact\Models\Contact')->whereIn('product_id',$list_products)->pluck('productable_id');
            return $this->where(function($q) use ($list_custom)
            {
                return $q->whereIn('id', $list_custom);
            });
        }
    }

    public function nguon($nguon)
    {
        return $this->where(function($q) use ($nguon)
        {
            return $q->where('type',  $nguon);
        });
    }

    public function datenew($dates)
    {
        $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        $dates[0] = $stardate->format('Y-m-d');
        $dates[1] = $enddate->format('Y-m-d');
        $lich = \DB::table('calendar_t')->where('start_t','>=',$dates[0])->where('end_t','<=',$dates[1])->pluck('id_table');
        $dr = DiemRoi::whereIn('id_calendar_t',$lich)->orderBy('id','desc')->pluck('id_khtn');
        // $unique = $dr->unique('id_khtn')->toArray();
        // $array = array_column($unique, 'id_khtn');
        // $arr =  array();
        // foreach ($dr as $key => $value) {
        //     $arr[] = $key;
        // }
        
        // dd($dates);
        return $this->where(function($q) use ($dr)
        {
            return $q->whereIn('id', $dr);
        });
    }
}


