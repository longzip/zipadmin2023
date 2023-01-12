<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Artisanry\Commentable\Traits\HasComments;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use NoiThatZip\Activity\Traits\HasActivities;
use NoiThatZip\Task\Traits\HasTasks;
use NoiThatZip\Lost\Traits\HasLosts;
use NoiThatZip\Kh15\Traits\HasKh15s;
use NoiThatZip\Video\Traits\HasVideos;
use NoiThatZip\Attachment\Traits\HasAttachments;
use NoiThatZip\Costcenter\Traits\HasCostcenters;
use NoiThatZip\Quote\Traits\HasQuotes;
use NoiThatZip\Order\Traits\HasOrders;
use NoiThatZip\ProductLine\Traits\HasProductLines;
use EloquentFilter\Filterable;
use PawelMysior\Publishable\Publishable;

class DaiLy extends Model implements HasMedia
{
    use HasComments;
	use HasMediaTrait;
    use HasActivities;
    use HasTasks;
    use HasLosts;
    use HasKh15s;
    use HasVideos;
    use HasCostcenters;
    use HasQuotes;
    use HasOrders;
    use Filterable;
    use HasProductLines;
    use Publishable;
    use HasAttachments;

    protected $table = "dai_ly";

	protected $guarded = ['id', 'created_at', 'updated_at'];

}
