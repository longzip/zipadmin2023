<?php

namespace NoiThatZip\Warehouse\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    //
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $table = 'warehouses';
}
