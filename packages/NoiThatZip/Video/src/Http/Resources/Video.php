<?php

namespace NoiThatZip\Video\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class Video extends JsonResource
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
            'created_at' => (string)$this->created_at,
            'creator_id' => $this->creator_id,
            'creator_type' => $this->creator_type,
            'id' => $this->id,
            'title' => $this->title,
            'updated_at' => $this->updated_at,
            'videolog_id' => $this->videolog_id,
            'videolog_type' => $this->videolog_type,
            'user_name' => User::find($this->creator_id)->name
        ];
    }
}
