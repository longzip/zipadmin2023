<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class ChotCong extends Model 
{

	use Filterable;
	
    protected $table = 'chot_cong';
}
