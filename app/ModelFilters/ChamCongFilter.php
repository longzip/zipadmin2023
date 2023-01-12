<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use App\ChamCong;

class ChamCongFilter extends ModelFilter
{
    public $relations = [];

    public function name($name)
    {
        return $this->where(function($q) use ($name)
        {
            return $q->where('name', 'LIKE', "%$name%");
        });
    }

    public function sdates($sdates)
    {
        return $this->where(function($q) use ($sdates)
        {
            return $q->whereBetween('date', $sdates);
        });
    }

    public function users($name)
    {
    	$ten = ChamCong::whereIn('id',$name)->pluck('name');
        return $this->where(function($q) use ($ten)
        {
            return $q->whereIn('name', $ten);
        });
    }

    public function bp($bp)
    {
        return $this->where(function($q) use ($bp)
        {
            return $q->whereIn('info', $bp);
        });
    }

    public function khoi($khoi)
    {
        return $this->where(function($q) use ($khoi)
        {
            return $q->where('type', $khoi);
        });
    }
}