<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class Product extends Model
{
    use Filterable;
	
    protected $fillable = ['code', 'name', 'price', 'point'];

    // public function quoteItems(){
    // 	return $this->
    // }
}
