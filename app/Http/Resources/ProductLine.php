<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Product;

class ProductLine extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $product = Product::find($this->product_id);
        return [
            'id' => $this->id,
            'code' => $product->code,
            'name' => $product->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'amount' => $this->amount,
            'point' => $this->point,
            'creator_name' => \App\User::find($this->creator_id)->name,
            'creator_id' => $this->creator_id
        ];
    }
}
