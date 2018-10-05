<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuctionModification extends Model
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
    protected $table = 'auction_modification';

    public function auction()
    {
        return $this->belongsTo('App\Auction', 'idapprovedauction', 'id');
    }
}
