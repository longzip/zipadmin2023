<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['last_name',	'title', 'address_line1', 'address_line2', 'city', 'country', 'phone', 'mobile', 'email', 'date_start',	'costcenter', 'job_title', 'code', 'warehouse',  ];

    public function user()
	{
	    return $this->belongsTo('App\User');
	}
}
