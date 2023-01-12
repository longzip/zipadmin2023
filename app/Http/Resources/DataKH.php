<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Data;

class DataKH extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'note' => $this->note,
            'address' =>  $this->address,
            'kh15' =>  $this->kh15,
            'type' =>  $this->type,
            'kv' =>  $this->kv_id,
        ];
    }

}
