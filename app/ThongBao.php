<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use EloquentFilter\Filterable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use NoiThatZip\Costcenter\Traits\HasCostcenters;
use Cmgmyr\Messenger\Traits\Messagable;
use Spatie\Activitylog\Traits\CausesActivity;
use Artisanry\Timeable\Traits\HasTimesTrait;
use NoiThatZip\SalesTarget\Traits\HasSalesTarget;
use Artisanry\Commentable\Traits\HasComments;

class ThongBao extends Model
{
    //
    use HasApiTokens, Notifiable, HasCostcenters, Messagable, Filterable, CausesActivity, HasTimesTrait, HasSalesTarget,HasComments;

    protected $table = "thongbao";
	protected $fillable = ['id_chuyenmuc','noidung','type ', 'user_see', 'user_tao'];

}
