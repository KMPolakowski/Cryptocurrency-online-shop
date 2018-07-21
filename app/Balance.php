<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    public $timestamps = false;

    public $primaryKey = 'user_id';

    public $attributes = [
        'eur' => 0,
        'pln' => 0,
        'usd' => 0
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
