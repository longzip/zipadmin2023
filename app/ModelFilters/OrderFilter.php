<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;
use NoiThatZip\Contact\Models\Contact;
use App\Customer;
use App\Project;
use App\Company;
use App\Product;
use App\OrderLine;
use NoiThatZip\Costcenter\Models\Costcenter;

class OrderFilter extends ModelFilter
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
            return $q->whereBetween('created_at', $dates);
        });
    }

    public function sdates($dates)
    {
        $enddate = new Carbon($dates[1]);
        $stardate = new Carbon($dates[0]);
        $dates[0] = $stardate->toDateString();
        $dates[1] = $enddate->toDateString();
        $conver = implode('',explode('-', $dates[0]));
        if($conver < 20181231){
            if (\Auth::user()->id == 9003 || \Auth::user()->id == 268 || \Auth::user()->id == 9074) {
                $date = $dates;
            }else{
                $date = ['2018-12-31','2019-12-29',$dates[2]];
            }
        }else{
            $date = $dates;
        }
        if($dates[2] == 1){
            return $this->where(function($q) use ($date)
            {
                return $q->whereBetween('delivery_date', [$date[0],$date[1]]);
            });
        }else{
            return $this->where(function($q) use ($date)
            {
                return $q->whereBetween('start_date', [$date[0],$date[1]]);
            });
        }
    }

    public function deliveryDate($deliveryDates)
    {
        // $stardate = new Carbon($dates[0]);
        $enddate = new Carbon($dates[1]);
        // $dates[0] = $enddate->startOfDay()->toDateTimeString();
        $dates[1] = $enddate->endOfDay()->toDateTimeString();
        return $this->where(function($q) use ($dates)
        {
            return $q->whereBetween('delivery_date', $dates);
        });
    }

    public function users($ids)
    {
        return $this->where(function($q) use ($ids)
        {
            return $q->whereIn('user_id', $ids);
        });
    }

    public function name($name)
    {
        return $this->where(function($q) use ($name)
        {
            return $q->where('so_number', 'LIKE', "%$name%")
                ->orWhere('note', 'LIKE', "%$name%");
        });
    }
    public function salesareas($ids)
    {
        $phone = Contact::where('salesarea_id',$ids)->pluck('phone');
        $idCustomer = Customer::whereIn('phone',$phone)->pluck('id');


        $idDA = Project::where('salesarea_id',$ids)->pluck('company_id');
        $phoneDA = Company::whereIn('id',$idDA)->pluck('phone_one');
        $idCustomerDA = Customer::whereIn('phone',$phoneDA)->pluck('id');

        $merged = $idCustomer->merge($idCustomerDA);
        // dd($merged);
        return $this->where(function($q) use ($merged)
        {
            return $q->whereIn('deliver_to', $merged);
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
    public function costcenter($ids)
    {
        $costcenter = Costcenter::whereIn('id',$ids)->pluck('code');
        return $this->where(function($q) use ($costcenter)
        {
            return $q->whereIn('costcenter', $costcenter);
        });
    }

    public function p($p)
    {
        $groups = Product::where('groups',$p)->pluck('id');
        $listID = OrderLine::whereIn('product_id',$groups)->pluck('order_id');
        return $this->where(function($q) use ($listID)
        {
            return $q->whereIn('id', $listID);
        });
    }
}
