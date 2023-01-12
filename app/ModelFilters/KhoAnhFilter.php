<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class KhoAnhFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function sanpham($sanpham)
    {
        return $this->where(function($q) use ($sanpham)
        {
            return $q->whereIn('sanpham',  $sanpham);
        });
    }

    public function phong($phong)
    {
         return $this->where(function($q) use ($phong)
         {
             return $q->where('name', $phong);
        });
    }

    public function loai($loai)
    {
         return $this->where(function($q) use ($loai)
         {
             return $q->where('loai', $loai);
        });
    }
}
