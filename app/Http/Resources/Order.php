<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Customer;
use App\User;
use App\Product;
use NoiThatZip\Contact\Models\Contact;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $customer = Customer::find($this->deliver_to);
        $id = 1;
        $user = User::find($this->user_id);
        if (isset($customer->phone)) {
            $id = Contact::where('phone',$customer->phone)->first('id');
            if (!empty($id->id)) {
                $id = $id->id;
            }
        }
        $amount = [$this->amount];
        $phone = isset($customer->phone) ? $customer->phone : '';
        $type =  Contact::where('phone',$phone)->orderBy('id','desc')->first();

        $cut = substr($this->description, 0, 2);
        $folder = 0;
        if ($cut == 'TK') {
            $folder = 1;
        }
        return [
            'folder' => $folder,
            'amount' => $this->amount,
            'costcenter' => $this->costcenter,
            'created_at' => (string)$this->created_at,
            'credit' => $this->credit,
            'customer_id' => $this->customer_id,
            'customer_address' => isset($customer->address_line1) ? $customer->address_line1 : '',
            'customer_name' => isset($customer->name) ? $customer->name : '',
            'customer_phone' => $phone,
            'debit' => $this->debit,
            'sdt' => $this->sdt,
            'xeploai' => isset($type) ? $type['type'] : 0,
            'deleted_at' => $this->deleted_at,
            'deliver_to' => $this->deliver_to,
            'delivery_date' => $this->delivery_date,
            'start_date' => $this->start_date,
            'delivery_method' => $this->delivery_method,
            'description' => $this->description,
            'export' => $this->export,
            'office' => $this->office,
            'fee_kh' => $this->fee_kh,
            'fee_ld' => $this->fee_ld,
            'fee_vc' => $this->fee_vc,
            'glaccount_id' => $this->glaccount_id,
            'id' => $this->id,
            'idContacts' => $id,
            'invoice_to' => $this->invoice_to,
            'journal_id' => $this->journal_id,
            'note' => $this->note,
            'ordered_by' => $this->ordered_by,
            'chia' => $this->cos_chia,
            'pre_pay' => $this->pre_pay,
            'qgg' => $this->qgg,
            'so_number' => $this->so_number,
            'status_id' => $this->status_id,
            'subtotal' => $this->subtotal,
            'type' => $this->type,
            'updated_at' => (string)$this->updated_at,
            'user_id' => $this->user_id,
            'user_name' => $user['name'],
            'vat' => $this->vat,
            'warehouse' => $this->warehouse,
            'your_ref' => $this->your_ref,
            'products'    => Product::select('id','code as name')->whereIn('id',$this->orderlines()->pluck('product_id'))->get(),
            'orderLine' => $this->orderlines,
            'test' => $amount,
            'status' => $this->status_order,
            'user_login' => \Auth::user()->id,
        ];
    }
}
