<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\ChienDichTarget;
use App\Product;

class ChienDich extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    	$target = ChienDichTarget::where('id_chiendich',$this->id)->pluck('product');
        $pro = Product::whereIn('groups',$target)->get()->unique('groups');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mess' => $this->mess,
            'phone' => $this->phone,
            'khtn' => $this->khtn,
            'doanhthu' => $this->doanhthu,
            'chiphi' => $this->chiphi,
            'time'  => $this->start.' đến '.$this->end,
            'start'  => $this->start,
            'end'  => $this->end,
            'products' => collect($pro->values()->all()),
        ];
    }
}
