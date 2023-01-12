<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Messages as MessagesResource;
use NoiThatZip\Contact\Models\Contact;
use App\Nghiphep;

class Threads extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $nghiphep = Nghiphep::where('id',$this->id)->first();
        $message = $this->getLatestMessageAttribute();
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'latestMessage' => new MessagesResource($message)
        ];
    }
}
