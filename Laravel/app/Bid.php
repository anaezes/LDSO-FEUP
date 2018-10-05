<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bid';

    public function user()
    {
        return $this->belongsTo('App\User', 'idbuyer', 'id');
    }

    public function auction()
    {
        return $this->belongsTo('App\Auction', 'idauction', 'id');
    }
}
