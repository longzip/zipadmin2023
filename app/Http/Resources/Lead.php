<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\ChienDich;
use App\DieuKien;
use App\Product;
use NoiThatZip\SalesArea\Models\SalesArea;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use NoiThatZip\Contact\Models\Contact;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use NoiThatZip\Quote\Http\Resources\QuoteLead as QuoteResource;
use NoiThatZip\Video\Http\Resources\Video as VideoResource;

class Lead extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $salesareas = $this->salesarea_id == Null ? '[0]' : $this->salesarea_id;
        $khuvuc = SalesArea::whereIn('id',json_decode($salesareas))->pluck('name');
        $SalesArea = SalesArea::whereIn('id',json_decode($salesareas))->get();
        $idContact = Contact::where('phone',$this->phone)->first();
        $chiendich = ChienDich::where('id',$this->chiendich)->first();
        $diemroi = DieuKien::where('phone',$this->phone)->first();
        $dr = array();
        if (!empty($diemroi)) {
            $dr = \DB::table('calendar_t')->where('id_table',$diemroi->diemroi)->first();
        }
        return [
            'id' => $this->id,            
            'diemroi' => $dr,
            'phonehide' => 'xxxxxx'.substr($this->phone, -4),
            'title'      => $this->title,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'phone'      => $this->phone,
            'zalo'      => $this->zalo,
            'email'      => $this->email,
            'checkbox'   => $this->statuskh == 2 ? true : false,
            'company'    => $this->company,
            'address'    => $this->address,
            'city'       => $this->city,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status'     => $this->status,
            'statuskh'     => $this->statuskh,
            'type'     => $this->type,
            'costcenters' => $this->costcentersList(),
            'products'    => Product::select('id','code as name')->whereIn('id',$this->productLines()->pluck('product_id'))->get(),
            'salesarea' => $SalesArea,
            'khuvuc' => $khuvuc,
            'idContact' => isset($idContact['id']) ? $idContact['id'] : '',
            'username'   => User::find($this->user_id)->name,
            'comments'   => $this->comments,
            'tasks'      =>  TaskResource::collection($this->tasks),
            'note'       =>  $this->note, 
            'created_at' => (string)$this->created_at,
            'quotes'     =>  QuoteResource::collection($this->quotes),
            'start_date_roi' => $this->start_nha,
            'videos'     =>  VideoResource::collection($this->videos),
            'end_date_roi' => $this->end_nha,
            'losts'      =>  $this->losts,
            'kh15s'      =>  $this->kh15s,
            'attachs'     =>  AttachmentResource::collection($this->attachs),
            'sanpham' => $this->sanpham,
            'chiendich' => empty($chiendich) ? null : $chiendich,
        ];
    }
}
