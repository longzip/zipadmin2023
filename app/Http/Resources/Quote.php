<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Product;

class Quote extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contact = \NoiThatZip\Contact\Models\Contact::find($this->quoteable_id);
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'pre_tax_total' => $this->pre_tax_total,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'discount_amount' => $this->discount_amount,
            'validtill' => $this->validtill,
            'contact_id' => $this->quoteable_id,
            'contact_name' => $contact->last_name,
            'contact_address' => $contact->address,
            'contact_city' => $contact->city,
            'contact_phone' => $contact->phone,
            'contact_email' => $contact->email,
            'creator_name' => \App\User::find($this->creator_id)->name,
            'creator_id' => $this->creator_id,
            'created_at' => (string)$this->created_at
        ];
    }
}
