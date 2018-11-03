<?php

namespace App;

use GuzzleHttp\Client;
use Mailgun\Mailgun;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone', 'username','idfaculty',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $primaryKey = 'id';

    /**
     *
     * This user's faculty
     *
     */
    public function faculty(){
        return $this->hasOne('App\Faculty','id','idfaculty');
    }

    /**
     *
     * This user's proposals
     *
     */
    public function proposals(){
       return $this->hasMany('App\Proposal','id','idproponent');
    }

    public function teams(){
        return $this->belongsToMany('App\Team', 'team_member', 'iduser', 'idteam');
    }

/*
    /**
     *
     * This user's bids
     *

    public function bids(){
       return $this->hasMany('App\Bid','id','idbuyer');
    }
    */

    /**
     *
     * This user's gets for the account status
     *
     */
    public function isBanned(){
        return $this->users_status=='banned';
    }

    public function isNormal(){
        return $this->users_status=='normal';
    }

    public function isTerminated(){
        return $this->users_status=='terminated';
    }    

    public function isAdmin(){
        return $this->users_status=='admin';
    }    

    public function isModerator(){
        return $this->users_status=='moderator';
    } 

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {

        //mailgun init
        $client = new Client([
            'base_uri' => 'https://api.mailgun.net/v3',
            'verify' => false,
        ]);
        $adapter = new \Http\Adapter\Guzzle6\Client($client);
        $domain = "sandboxeb3d0437da8c4b4f8d5a428ed93f64cc.mailgun.org";
        $mailgun = new \Mailgun\Mailgun('key-44a6c35045fe3c3add9fcf0a018e654e', $adapter);

        # Send the email
        $result = $mailgun->sendMessage("$domain",
            array('from' => 'Home remote Sandbox <postmaster@sandboxeb3d0437da8c4b4f8d5a428ed93f64cc.mailgun.org>',
                'to' => $this->name.' <'.$this->email.'>',
                'subject' => 'Reset password',
                'text' => 'Someone asked for password reset a contact message using the contact page. the token is: '.$token.'
                To reset your password please visit http://lbaw1726.lbaw-prod.fe.up.pt/password/reset/'.$token,
                'require_tls' => 'false',
                'skip_verification' => 'true',
            ));
    }          
}
