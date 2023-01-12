<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use NoiThatZip\ProductLine\Traits\HasProductLines;

class Marketing extends Model
{
	use HasProductLines;

    protected $table = "marketing";

}
