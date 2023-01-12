<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Product;
use App\DieuKien;
use App\ChienDich;
use App\DiemRoi;
use NoiThatZip\Lead\Models\Lead;
use App\Customer;
use App\OrderLine;
use App\Order;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use NoiThatZip\SalesArea\Models\SalesArea;
use NoiThatZip\Quote\Http\Resources\Quote as QuoteResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use NoiThatZip\Video\Http\Resources\Video as VideoResource;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use NoiThatZip\Quote\Http\Resources\QuoteLead as QuoteLeadResource;

class Contact extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $order = []; $orders = [];
        // $customer = Customer::where('phone', $this->phone)->first();
        // if ($customer) {
        //     $order = $customer->orders;
        // }
        // foreach ($order as $key => $value) {
        //     $order = OrderLine::where('order_id',$value['id'])->get();
        //     $customers = Customer::find($value->deliver_to);
        //     $orders[$key] = $value;
        //     $orders[$key]['customer'] = $customers;
        //     $orders[$key]['orderLine'] = $order;
        // }
        $customer = Customer::where('phone', $this->phone)->get();
        
        foreach ($customer as $key => $value) {
            $don = Order::where('deliver_to',$value['id'])->first();
            $order = OrderLine::where('order_id',$don['id'])->get();
            $customers = Customer::find($value->id);
            $orders[$key] = $don;
            $orders[$key]['customer'] = $customers;
            $orders[$key]['orderLine'] = $order;
        }
        $salesareas = $this->salesarea_id == Null ? '[0]' : $this->salesarea_id;
        $khuvuc = SalesArea::whereIn('id',json_decode($salesareas))->pluck('name');
        $SalesArea = SalesArea::whereIn('id',json_decode($salesareas))->get();
        $id = \Auth::user()->id;
        $sm = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',6)->get();
        $chiendich = ChienDich::where('id',$this->chiendich)->first();
        $check = ($sm->isEmpty()) ? 0 : 1;
        $dr=array();
        $diemroi = DiemRoi::where('id_khtn',$this->id)->get();
        if ($diemroi->isNotEmpty()) {
            $lich = DiemRoi::where('id_khtn',$this->id)->orderBy('id','desc')->first();
            $dr = \DB::table('calendar_t')->where('id_table',$lich['id_calendar_t'])->first();
        }

        $losts = $this->losts;
        $kh15s = $this->kh15s;
        $videos = $this->videos;
        $comments = $this->comments;
        $attachs = $this->attachs;
        $tasks = $this->tasks;
        $quotes = QuoteResource::collection($this->quotes);

        $phone = Lead::where('phone',$this->phone)->get();
        $dk = DieuKien::where('phone',$this->phone)->get();
        foreach ($dk as $value) {
            if (count($value->attachs) > 0) {
                foreach ($value->attachs as $v) {
                    $attachs[count($attachs) + 1] = $v;
                }
            }
        }
        foreach ($phone as $value) {
            if (count($value->losts) > 0) {
                foreach ($value->losts as $v) {
                    $losts[count($losts) + 1] = $v;
                }
            }
            if (count($value->kh15s) > 0) {
                foreach ($value->kh15s as $v) {
                    $kh15s[count($kh15s) + 1] = $v;
                }
            }
            if (count($value->videos) > 0) {
                foreach ($value->videos as $v) {
                    $videos[count($videos) + 1] = $v;
                }
            }
            if (count($value->comments) > 0) {
                foreach ($value->comments as $v) {
                    $comments[count($comments) + 1] = $v;
                }
            }
            if (count($value->attachs) > 0) {
                foreach ($value->attachs as $v) {
                    $attachs[count($attachs) + 1] = $v;
                }
            }
            if (count($value->tasks) > 0) {
                foreach ($value->tasks as $v) {
                    $tasks[count($tasks) + 1] = $v;
                }
            }
            if (QuoteLeadResource::collection($value->quotes)->count() > 0) {
                foreach (QuoteLeadResource::collection($value->quotes) as $v) {
                    $quotes[QuoteResource::collection($value->quotes)->count() + 1] = $v;
                }
            }
        }

        $dk = array();
        $ar = DieuKien::where('phone',$this->phone)->first();
        if (!empty($ar)) {
            $dk['matbang'] = $ar->matbang;            
            $dk['ngansach'] = $ar->ngansach;
            $dk['doituong'] = $ar->doituong;            
            $dk['t'] = $ar->t;
            $dk['diemroi'] = \DB::table('calendar_t')->where('id_table',$ar->diemroi)->first();
        }

        return [
            'id' => $this->id,
            'title'      => $this->title,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'phone'      => $this->phone,
            'dieukien'      => $dk,
            'chiendich' => empty($chiendich) ? null : $chiendich,
            'email'      => $this->email,
            'zalo'      => $this->zalo,
            'company'    => $this->company,
            'address'    => $this->address,
            'city'       => $this->city,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duyet' => $this->duyet,
            'status'     => (integer)$this->status,
            'costcenters' => $this->costcentersList(),
            'products'    => Product::select('id','code as name')->whereIn('id',$this->productLines()->pluck('product_id'))->get(),
            'salesarea' => $SalesArea,
            'khuvuc' => $khuvuc,
            'user_id'    => $this->user_id,
            'username'   => User::find($this->user_id)->name,
            'amount'     =>  $this->quotes->max('total'),
            'tasks'      =>  TaskResource::collection($tasks->values()),
            'quotes'     =>  $quotes,
            'medias'     =>  $this->getMedia(),
            'videos'     =>  VideoResource::collection($videos->values()),
            'attachs'     =>  AttachmentResource::collection($attachs->values()),
            'kh15s'      =>  $kh15s->values(),
            'losts'      =>  $losts->values(),
            'comments'      =>  $comments->values(),
            'note'      =>  $this->note,
            'type'      =>  $this->type,
            'isPublished' => $this->isPublished(),
            'orders'    => $orders,
            'isUnread'    => $this->thread_id,
            'created_at' => (string)$this->created_at,
            'sm' => $check,
            'diemroi' => $dr,
            'loai' => $this->loai,
            'sales' => \Auth::user()->hasRole('sales') ? 1 : 0,
        ];
    }
}
