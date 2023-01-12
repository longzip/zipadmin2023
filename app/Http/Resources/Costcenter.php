<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Costcenter extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'id' => $this->id,
            'name' => $this->name,
            'code'  => $this->code,
            'user_id'      => $this->user_id,
            'username'   => User::find($this->user_id)->name,
            'created_at' => (string)$this->created_at
        ];
    }
}
