<?php

namespace NoiThatZip\Quote\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Product;

class Quoteda extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contact = \App\Project::find($this->quoteable_id);
        $company = \App\Company::find($contact->company_id);
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'pre_tax_total' => $this->pre_tax_total,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'qgg' => $this->qgg,
            'fee_vc' => $this->fee_vc,
            'fee_ld' => $this->fee_ld,
            'vat' => $this->vat,
            'validtill' => $this->validtill,
            'contact_id' => $this->quoteable_id,
            'company' => $company->name_company,
            'company_sapo' => $company->sapo,
            'contact_city' => $contact->city,

            'company_name_one' => $company->name_one ,
            'company_chucdanh_one' => $company->chucdanh_one ,
            'company_phone_one' => $company->phone_one,
            'company_address_one' => $company->address_one, 
            'company_email_one' => $company->email_one, 

            'company_name_two' => $company->name_two ,
            'company_chucdanh_two' => $company->chucdanh_two,
            'company_phone_two' => $company->phone_two,
            'company_address_two' => $company->address_two, 
            'company_email_two' => $company->email_two,             
            'products'    => Product::select('id','code as name')->whereIn('id',$this->productLines()->pluck('product_id'))->get(),
            'creator_name' => \App\User::find($this->creator_id)->name,
            'creator_id' => $this->creator_id,
            'created_at' => (string)$this->created_at
        ];
    }
}
