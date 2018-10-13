<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkillProposal extends Model
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
    protected $table = 'skill_proposal';

    protected $primaryKey = 'idProposal';
}
