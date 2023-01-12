<?php

namespace NoiThatZip\SalesArea\Models;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;
use NoiThatZip\SalesArea\ModelFilters\SalesAreaFilter;

class SalesArea extends Model
{
    use Filterable;
    //
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function modelFilter()
    {
        return $this->provideFilter(SalesAreaFilter::class);
    }

}
