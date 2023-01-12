<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class SanXuat extends Model 
{
	use Filterable;
	
    protected $table = 'sanxuat';
}
