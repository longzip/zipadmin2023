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


class User extends Authenticatable
{
use HasApiTokens, Notifiable, HasRoles, HasCostcenters, Messagable, Filterable, CausesActivity, HasTimesTrait, HasSalesTarget;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pivot'
    ];

    public function resource()
    {
        return $this->hasOne('App\Resource');
    }

    public function contacts()
    {
        return $this->hasMany('\NoiThatZip\Contact\Models\Contact');
    }

    public function leads()
    {
        return $this->morphMany('\NoiThatZip\Lead\Models\Lead', 'creator');
    }
}