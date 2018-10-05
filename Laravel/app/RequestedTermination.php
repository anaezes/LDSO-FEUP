<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestedTermination extends Model
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
    protected $table = 'requested_termination';

    /**
     *
     * The user that created this auction
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'idusers', 'id');
    }
}
