<?php

namespace NoiThatZip\SalesArea\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesArea extends JsonResource
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
