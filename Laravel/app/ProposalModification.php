<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProposalModification extends Model
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
    protected $table = 'proposal_modification';

    public function proposal()
    {
        return $this->belongsTo('App\Proposal', 'idapprovedproposal', 'id');
    }
}
