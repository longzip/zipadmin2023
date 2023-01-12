<?php

declare(strict_types=1);

/*
 * This file is part of Laravel CMR.
 *
 * (c) Lo Long <longlv@noithatzip.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NoiThatZip\Lead\Models;

use Illuminate\Database\Eloquent\Model;
use Artisanry\Commentable\Traits\HasComments;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use NoiThatZip\Activity\Traits\HasActivities;
use NoiThatZip\Task\Traits\HasTasks;
use NoiThatZip\Lost\Traits\HasLosts;
use Spatie\ModelStatus\HasStatuses;
use NoiThatZip\Kh15\Traits\HasKh15s;
use NoiThatZip\Video\Traits\HasVideos;
use NoiThatZip\Attachment\Traits\HasAttachments;
use NoiThatZip\Costcenter\Traits\HasCostcenters;
use NoiThatZip\Quote\Traits\HasQuotes;
use NoiThatZip\Order\Traits\HasOrders;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use NoiThatZip\ProductLine\Traits\HasProductLines;
use EloquentFilter\Filterable;
use PawelMysior\Publishable\Publishable;

class Lead extends Model implements HasMedia
{
    use HasComments;
    use HasMediaTrait;
    use HasActivities;
    use HasTasks;
    use HasLosts;
    use HasKh15s;
    use HasVideos;
    use HasCostcenters;
    use SoftDeletes;
    use HasStatuses;
    use HasProductLines;
    use HasQuotes;
    use HasOrders;
    use Filterable;
    use Publishable;
    use HasAttachments;

    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    // protected static $logAttributes = ['name', 'phone', 'address'];

    public function modelFilter()
    {
        return $this->provideFilter(\App\ModelFilters\LeadFilter::class);
    }
}
