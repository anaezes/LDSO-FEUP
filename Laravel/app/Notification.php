<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
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
    protected $table = 'notification';

    public function proposal(){
        return $this->belongsTo('App\Proposal', 'idproposal');
    }

    public function user(){
        return $this->belongsTo('App\User', 'idusers');
    }
}
