<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;
use Carbon\Carbon;

class NghiPhepFilter extends ModelFilter
{
    public $relations = [];

    
    public function user($user_id)
    {
        return $this->where(function($q) use ($user)
        {
            return $q->whereIn('ten_nhan_vien', $user);
        });
    }

    public function sdates($sdates)
    {
        return $this->where(function($q) use ($sdates)
        {
            return $q->whereBetween('created_at',$sdates);
        });
    }

    public function nhanvien($nhanvien)
    {
        return $this->where(function($q) use ($nhanvien)
        {
            if($nhanvien != 0){
	            return  $q->where('ten_nhan_vien',$nhanvien); 
            }
        });
    }

}