<?php

namespace App\Http\Resources;

use NoiThatZip\Costcenter\Models\Costcenter;
use Illuminate\Http\Resources\Json\JsonResource;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\User;
use App\ThiCong;

class BCTC extends JsonResource
{
    public function toArray($request)
    {
        $data = ThiCong::find($this->id_tc);
        $d = array();
        $pro = array();
        $sp = json_decode($data->product);
            
        foreach ($sp as $k => $v) {
            $v = json_decode($v);
            $pro[$k]['item'] =  $v->item;
            $pro[$k]['amount'] = $v->amount;
            $pro[$k]['quantity'] =  $v->quantity;
            $pro[$k]['pos'] =  'Nhà Máy';
        }
            
        $d['id'] = $data->id;          
        $d['costcenter'] = $data->costcenter;           
        $d['date'] = $data->delivery_date;
        $d['info'] = $data->name.' / '.$data->phone.' / '.$data->delivery_date;
        $d['phone'] = $data->phone;
        $d['so_number'] = $data->so_number;
        $d['address'] = $data->address;
        $d['name'] = $data->name;
        $d['type'] = $data->type;
        $d['start_date'] = $data->start_date;
        $d['phone'] = $data->phone;
        $d['so_number'] = $data->so_number;
        $d['address'] = $data->address;
        $d['product'] = $pro;
        $d['status'] = $data->status;
        $d['delivery_date'] = $data->delivery_date;
        return [     
        // $n->id_tc = $request->id;
            'so_number' => $d,
            'check' => $data->type,
            'admin' => \Auth::user()->hasRole('BP Admin') ? 1 : 0,
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->type,
            'product' => $pro,
            'mota' => $this->mota,
            'dathu' => $this->dathu,
            'note' => $this->note,
            'dathu' => $this->dathu,
            'phatsinh' => $this->phatsinh,
            'id_tc' => $this->id_tc,
            'attachs'     =>  AttachmentResource::collection($this->attachs),
        ];
    }
}
