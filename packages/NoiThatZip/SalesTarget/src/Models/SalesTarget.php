<?php
declare(strict_types=1);

namespace NoiThatZip\SalesTarget\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use EloquentFilter\Filterable;

class SalesTarget extends Model
{
    use Filterable;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function targetable(): MorphTo
    {
        return $this->morphTo();
    }
}



