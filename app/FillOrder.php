<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use NoiThatZip\Costcenter\Traits\HasCostcenters;

class FillOrder extends Model
{
    //
	use Filterable,HasCostcenters;

    protected $fillable = ['so_number','description','your_ref','resource','ordered_by','deliver_to','invoice_to','warehouse','costcenter', 'vat', 'subtotal', 'note', 'amount', 'fee_kh', 'fee_ld', 'fee_vc','type', 'qgg'];

    public function __construct()
    {
    if($_POST["is_date_search"] == "yes")
		{
		 $query .= 'order_date BETWEEN "'.$_POST["start_date"].'" AND "'.$_POST["end_date"].'" AND ';
		}

    }
}
