<?php

namespace App;

use App\Crypto;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function coin_balances()
    {
        return $this->hasMany('App\Coin_balance');
    }

    public function balances()
    {
        return $this->hasOne('App\Balance');
    }

}
