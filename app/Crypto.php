<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crypto extends Model
{

    public $timestamps = false;

    protected $primaryKey = 'user_id';

    protected $attributes = [
        'Bitcoin' => 0,
        'Ethereum' => 0,
        'XRP' => 0,
        'Bitcoin Cash' => 0,
        'EOS' => 0,
        'Litecoin' => 0,
        'Stellar' => 0,
        'Cardano' => 0,
        'IOTA' => 0,
        'Tether' => 0
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
