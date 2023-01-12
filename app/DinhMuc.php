<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class DinhMuc extends Model 
{
	use Filterable;
	
    protected $table = 'dinhmuc';
}
