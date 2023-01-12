<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use NoiThatZip\Costcenter\Traits\HasCostcenters;

class Order extends Model
{
    //
	use Filterable,HasCostcenters;

    protected $fillable = ['so_number','description','your_ref','resource','ordered_by','deliver_to','invoice_to','warehouse','costcenter', 'vat', 'subtotal', 'note', 'amount','trangthai', 'fee_kh', 'fee_ld', 'fee_vc','type', 'qgg'];

    public function orderlines()
    {
        return $this->hasMany('App\OrderLine');
    }
}
