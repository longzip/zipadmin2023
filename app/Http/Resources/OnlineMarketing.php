<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Order;
use App\ChienDich;
use App\ChienDichTarget;
use App\Online;
use App\Product;
use App\Customer;
use App\OnlineLine;
use NoiThatZip\Lead\Models\Lead;
use NoiThatZip\Contact\Models\Contact;
use App\OrderLine;
use DateTime;

class OnlineMarketing extends JsonResource
{
    public function toArray($request)
    {
    	$donhang = Order::whereDate('start_date',$this->date)->Where('costcenter','ON_MB')->orWhere('costcenter','ON_MN')->pluck('id');
        $chiendich = ChienDich::where('id',$this->id_chiendich)->first();
        $date1 = new DateTime($chiendich['start']);
        $date2 = new DateTime($chiendich['end']);
        $interval = $date1->diff($date2);
        $target = ChienDichTarget::where('id_chiendich',$this->id_chiendich)->pluck('product');
        $pro = Product::whereIn('groups',$target)->get()->unique('groups');
        $targetmess = round($chiendich['mess'] / ($interval->days + 1));
        $targetphone = round($chiendich['phone'] / ($interval->days + 1));
        $targetkhtn = round($chiendich['khtn'] / ($interval->days + 1));
        $khtn = Contact::where('start_date',$this->date)->where('chiendich',$this->id_chiendich)->where('type',2)->count();
        $mess = Lead::where('start_date',$this->date)->where('chiendich',$this->id_chiendich)->where('type',2)->count();

        $check = Contact::where('start_date',$this->date)->where('chiendich',$this->id_chiendich)->where('type',2)->pluck('phone');
        $cus = Customer::whereIn('phone',$check)->pluck('id');
        $or =  Order::whereIn('deliver_to',$cus)->get();

        return [
            'number' => $or->isEmpty() ? 0 : $or->count(),
            'sum' => $or->isEmpty() ? 0 : $or->sum('amount') ,
            'id' => $this->id,
            'chiendich' => $chiendich,
            'namechiendich' => $chiendich['name'],
            'product' => $pro,
            'khtn' => $khtn,
            'phone' => $mess,
            'chiphi' => $this->chiphi,
            'mess' => $this->mess,
            'chiphitarget' => round($chiendich['chiphi'] / ($interval->days + 1)),
            'date' => $this->date,
            'targetmess' => $targetmess,
            'targetphone' => $targetphone,
            'targetkhtn' => $targetkhtn,

        ];
    }
}
