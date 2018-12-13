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
    public $table = 'team';

    /**
    * The primary key associated with the model
    *
    * @var string
    */
    public $primaryKey = "id";

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
    public function user()
    {
        return $this->belongsTo('App\User', 'idleader', 'id');
    }

    /**
    * The members of this team
    *
    */
    public function members()
    {
        return $this->belongsToMany('App\User', 'team_member', 'idteam', 'iduser');
    }

    /**
    * The faculties associated with this team
    *
    */
    public function faculties()
    {
        return $this->belongsToMany('App\Faculty', 'team_faculty', 'idteam', 'idfaculty');
    }

    /**
     * Scope a query to search teams based on their name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $text
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->whereRaw("searchtext @@ plainto_tsquery('english', ?)", [$search])
                     ->orderByRaw("ts_rank(searchtext, plainto_tsquery('english', ?)) DESC", [$search]);
    }
}
