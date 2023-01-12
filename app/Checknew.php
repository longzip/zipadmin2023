<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checknew extends Model
{
    protected $table = "checknews";

    protected $fillable = [
	    'user_id'
	];
}
