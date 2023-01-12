<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActivityTypes extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
