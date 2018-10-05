<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
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
    protected $table = 'image';

    public function user()
    {
        return $this->belongsTo('App\User', 'idusers', 'id');
    }

    public function auction()
    {
        return $this->belongsTo('App\Auction', 'idAuction', 'id');
    }

    public function auction_modification()
    {
        return $this->belongsTo('App\AuctionModification', 'idAuctionModification', 'id');
    }
}
