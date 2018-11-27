<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
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
    protected $table = 'faculty';

    protected $primaryKey = "id";


    /**
    * The proposals associated with this faculty
    *
    */
    public function proposal()
    {
        return $this->belongsToMany('App\Proposal', 'faculty_proposal', 'idfaculty', 'idproposal');
    }

    /**
    * The teams associated with this faculty
    *
    */
    public function teams()
    {
        return $this->belongsToMany('App\Team', 'team_faculty', 'idfaculty', 'idteam');
    }
}
