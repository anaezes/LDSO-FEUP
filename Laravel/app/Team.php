<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
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
    protected $table = 'team';

    protected $primaryKey = "id";

    /**
     *
     * The user that created this team
     *
     */

   public function user(){
        return $this->belongsTo('App\User', 'idleader', 'id');
   }

    public function members(){
        return $this->belongsToMany('App\User', 'team_member', 'idteam', 'iduser');
    }

}
