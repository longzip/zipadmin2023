<?php

namespace NoiThatZip\Warehouse\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Warehouse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        
    }
}
