<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;

class OrderLine extends Model
{
    //
    protected $fillable = ['item','order_id','product_id','quantity','price','amount','discount','delivery','warehouse','recipe','costcenter','resource'];

    public function order()
	{
	    return $this->belongsTo('App\Order');
	}
}
