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

    protected $table = "chiphi";
	protected $fillable = ['name','User_id','role_id', 'NgayTao', 'TongChiPhi','TongTienThuc','TinhTrang'];

    public function ChiPhiDetail()
    {
        return $this->hasMany('App\ChiPhiDetail');
    }
    public function loaichiphi()
    {
        return $this->hasMany('App\LoaiChiPhi');
    }
}
 