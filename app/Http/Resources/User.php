<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'email' => $this->email,
            'updated_at' => (string)$this->updated_at,
            'username' => $this->username,
            'roles' => $this->getRoleNames(),
            'off' => ($this->date_off == '2090-01-01') ? "" : $this->date_off,
            'costcenters' => $this->costcentersList()
        ];
    }
}
