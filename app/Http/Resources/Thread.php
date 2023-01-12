<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Message as MessageResource;
use NoiThatZip\Contact\Models\Contact;
use App\Nghiphep;

class Thread extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contact = Contact::where('thread_id',$this->id)->first();
        $message = $this->getLatestMessageAttribute();
        return [
            'id' => $this->id,
            'contact' => $contact['id'],
            'subject' => $this->subject,
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'deleted_at' => (string)$this->deleted_at,
            'latestMessage' => new MessageResource($message)
        ];
    }
}
