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

  /**
  * The primary key associated with the model
  *
  * @var string
  */
  protected $primaryKey = "id";

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = ['teamname', 'idLeader', 'teamdescription'];

  /**
  * The leader of this team
  *
  */
  public function user(){
    return $this->belongsTo('App\User', 'idleader', 'id');
  }

  /**
  * The members of this team
  *
  */
  public function members(){
    return $this->belongsToMany('App\User', 'team_member', 'idteam', 'iduser');
  }

  /**
  * The faculties associated with this team
  *
  */
  public function faculties(){
    return $this->belongsToMany('App\Faculty', 'team_faculty', 'idteam', 'idfaculty');
  }
}
