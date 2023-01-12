<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkduan extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = "checkduan";
}
