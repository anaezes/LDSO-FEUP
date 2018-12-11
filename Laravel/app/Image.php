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

    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo('App\User', 'idusers', 'id');
    }

    public function proposal()
    {
        return $this->belongsTo('App\Proposal', 'idproposal', 'id');
    }

    public function proposalModification()
    {
        return $this->belongsTo('App\ProposalModification', 'idproposalModification', 'id');
    }
}
