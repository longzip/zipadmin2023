<?php 
namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ViPhamFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function quydinh($decision_id)
    {
        return $this->where(function($q) use ($decision_id)
        {
            return $q->whereIn('user_id', $decision_id);
        });
    }

    public function user($user_id)
    {
        return $this->where(function($q) use ($user_id)
        {
            return $q->whereIn('user_id', $user_id);
        });
    }
    public function search_name($tenvipham)
    {
        return $this->where(function($q) use ($tenvipham)
        {
            return $q->whereIn('ten_vi_pham', $tenvipham);
        });
    }
}
