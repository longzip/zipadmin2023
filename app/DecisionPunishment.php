<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use NoiThatZip\Costcenter\Traits\HasCostcenters;
use Cmgmyr\Messenger\Traits\Messagable;
use EloquentFilter\Filterable;
use Spatie\Activitylog\Traits\CausesActivity;
use Artisanry\Timeable\Traits\HasTimesTrait;
use NoiThatZip\SalesTarget\Traits\HasSalesTarget;
use Illuminate\Database\Eloquent\Model;

class DecisionPunishment extends Model
{
    use HasApiTokens, Notifiable, HasRoles, HasCostcenters, Messagable, Filterable, CausesActivity, HasTimesTrait, HasSalesTarget;

    protected $table = 'decision_punishment';

    protected $fillable = [
        'name'
    ];

}