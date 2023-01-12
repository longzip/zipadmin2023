<?php

namespace NoiThatZip\News\Models;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;
use NoiThatZip\News\ModelFilters\NewsFilter;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use Filterable,SoftDeletes;

    protected $table = 'news';
    //
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function modelFilter()
    {
        return $this->provideFilter(NewsFilter::class);
    }

}
