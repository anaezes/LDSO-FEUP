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

    protected $primaryKey = "id";

    /**
     *
     * The user that created this proposal
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'idproponent', 'id');
    }

    public function faculty(){
        return $this->belongsToMany('App\Faculty', 'faculty_proposal', 'idproposal', 'idfaculty');
    }


    public function skill(){
        return $this->belongsToMany('App\Skill', 'skill_proposal', 'idproposal', 'idskill');
    }



}
