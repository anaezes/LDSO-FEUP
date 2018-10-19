<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
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
    protected $table = 'skill';

    protected $primaryKey = "id";

    public function proposal(){
        return $this->belongsToMany('App\Proposal', 'skill_proposal', 'idskill', 'idproposal');
    }

}
