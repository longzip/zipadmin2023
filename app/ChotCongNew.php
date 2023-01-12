<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;

class ChotCongNew extends Model 
{

	use Filterable;
	
    protected $table = 'chot_cong_new';
}
