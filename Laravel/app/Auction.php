<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
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
    protected $table = 'auction';

    /**
     *
     * The user that created this auction
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'idseller', 'id');
    }

    /**
     *
     * This auction's language
     *
     */
    public function language()
    {
        return $this->hasOne('App\Language', 'id', 'idlanguage');
    }

    /**
     *
     * This auction's publisher
     *
     */
    public function publisher()
    {
        return $this->hasOne('App\Publisher', 'id', 'idpublisher');
    }
}
