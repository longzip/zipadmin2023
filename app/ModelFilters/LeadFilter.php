<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Lead\Models\Lead;
use App\LogStatus;
use App\User;

class LeadFilter extends ModelFilter
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
            $check = substr($name,0,1);
            if ($check == '@') {
                $ten = substr($name,1);
                $user = User::where('name', 'LIKE', "%$ten%")->pluck('id');
                return $q->whereIn('user_id',$user);
            }else{
                return $q->where('first_name', 'LIKE', "%$name%")
                    ->orWhere('last_name', 'LIKE', "%$name%")
                    ->orWhere('phone', 'LIKE', "%$name%")
                    ->orWhere('address', 'LIKE', "%$name%")
                    ->orWhere('zalo', 'LIKE', "%$name%")
                    ->orWhere('city', 'LIKE', "%$name%");
            }
        });
    }

    public function users($ids)
    {
        return $this->where(function($q) use ($ids)
        {
            return $q->whereIn('user_id', $ids);
        });
    }

    public function chiendich($chiendich)
    {
        return $this->where(function($q) use ($chiendich)
        {
            return $q->where('chiendich', $chiendich);
        });
    }

    public function dates($dates)
    {
        $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        $dates[0] = $stardate->format('Y-m-d');
        $dates[1] = $enddate->format('Y-m-d');
        // dd($dates);
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('created_at', $dates);
        });
    }

    public function sdates($dates)
    {
        $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        $dates[0] = $stardate->format('Y-m-d');
        $dates[1] = $enddate->format('Y-m-d');
        // dd($dates);
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('start_date', $dates);
        });
    }

    public function salesareas($ids)
    {
        $all = Lead::whereNotNull('salesarea_id')->pluck('salesarea_id','id');
        foreach ($ids as $i) {
            foreach ($all as $key => $value) {
                foreach (json_decode($value) as $k => $v) {
                    if ((integer) $i == $v) {
                        $id[] = $key;
                    }
                }
            }
        }
        return $this->where(function($q) use ($id)
        {
            return $q->whereIn('id', $id);
        });
    }
    public function costcenter($ids)
    {
        $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->whereIn('costcenter_id',$ids)->pluck('model_id');
        
        return $this->where(function($q) use ($id)
        {
            return $q->whereIn('id', $id);
        });
    }
    public function p($p)
    {
        // dd($p);
        $list_products = \DB::table('products')->where('groups',$p)->pluck('id');
        if($list_products->isNotEmpty()){
            $list = $list_products;
            $list_custom = \DB::table('product_lines')->where('productable_type','NoiThatZip\Lead\Models\Lead')->whereIn('product_id',$list_products)->pluck('productable_id');
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

    public function khuvucs($khuvucs){
        $all = Lead::whereNotNull('salesarea_id')->pluck('salesarea_id','id');
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

    public function status($status)
    {
        $phone = Contact::where('status', $status)->pluck('phone');
        $phonekh = Lead::where('statuskh',$status)->pluck('phone');
        $phonekdung = Contact::where('status','!=', $status)->pluck('phone');
        $result = array_diff($phonekh->toArray(), $phonekdung->toArray());

        $merge = array_merge($result,$phone->toArray());
        return $this->where(function($q) use ($merge)
        {
            return $q->whereIn('phone', $merge);
        });
    }

    public function getkhtn($getkhtn)
    {
        if ($getkhtn == 1) {
            $phone = Contact::pluck('phone');
            return $this->where(function($q) use ($phone)
            {
                return $q->whereIn('phone', $phone);
            });
        }
    }

    public function marketing($marketing)
    {
        $idkhtn = LogStatus::where('name', 'LIKE', "%$marketing%")->pluck('id_khtn');
        $phone = Contact::whereIn('id', $idkhtn)->pluck('phone');
        return $this->where(function($q) use ($phone)
        {
            return $q->whereIn('phone', $phone);
        });
    }


    public function datenew($dates)
    {
        // $stardate = new Carbon($dates[0]);
        // $enddate = new Carbon($dates[1]);
        // $dates[0] = $stardate->format('Y-m-d');
        // $dates[1] = $enddate->format('Y-m-d');
        // dd($dates);
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('start_date', $dates);
        });
    }
}
