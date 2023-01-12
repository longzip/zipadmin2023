<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use NoiThatZip\Order\Traits\HasOrders;

class Customer extends Model
{
    //
    use HasOrders;
    
    protected $fillable = ['name','title','first_name','last_name','address_line1','address_line2','address_line3','city','phone','vat_number','email'];

    public function orders()
    {
        return $this->hasMany('App\Order','deliver_to');
    }
}
