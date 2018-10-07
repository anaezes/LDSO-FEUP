<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
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
    protected $table = 'proposal';

    /**
     *
     * The user that created this proposal
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'idseller', 'id');
    }

    /**
     *
     * This proposal's language
     *
     */
    public function language()
    {
        return $this->hasOne('App\Language', 'id', 'idlanguage');
    }

    /**
     *
     * This proposal's publisher
     *
     */
    public function publisher()
    {
        return $this->hasOne('App\Publisher', 'id', 'idpublisher');
    }
}
