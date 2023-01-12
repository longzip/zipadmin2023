<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class ChamCong extends Model 
{

	use Filterable;
	
    protected $table = 'cham_cong';
    protected $guarded = [];
	protected $dates = ['date'];
}
