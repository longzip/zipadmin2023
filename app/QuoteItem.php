<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    protected $fillable = ['product_id','quantity','listprice','discount_percent','quote_id'];

    public function product(){
    	return $this->belongsTo('App\Product','product_id');
    }
}
