<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ThongBaoFilter extends ModelFilter
{
    public $relations = [];

    public function name($name)
    {
        return $this->where(function($q) use ($name)
        {
            return $q->where('name', 'LIKE', "%$name%")->orWhere('dinh_muc', 'LIKE', "%$name%");
        });
    }
	public function user()
    {
        return $this->where(function($q)
        {
            return $q->where('user_see',\Auth::User()->id);
        });
    }

}