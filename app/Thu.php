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

class ChiPhi extends Model
{
    //
    use HasApiTokens, Notifiable, HasCostcenters, Messagable, Filterable, CausesActivity, HasTimesTrait, HasSalesTarget,HasComments;

    protected $table = "thu";
	protected $fillable = ['loaithu','donhang_id','showroom_id', 'ngaythu', 'ngaytienve','sotien','mota','bank_id','danhsachvay_id'];

}
