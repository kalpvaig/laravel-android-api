<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'token',
        'tokenGen'
    ];

    public $answers_if_answered = array();
    
    
    public $is_answered ;

    public $total_shares = array();
    
    public $posted_by = array();
    
    public $poll = array();
    /**
     * A user can have many polls
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function polls(){
        return $this->hasMany('\App\Poll');
    }

    /*
     * A user can have many answers
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function answers(){
        return $this->hasMany('\App\Answer');
    }

    public function sharedWith(){
        return $this->belongsToMany('\App\User','polls_share','user_id','user_id_shared')->withPivot('poll_id');
    }

    public function feeds(){
        return $this->belongsToMany('\App\Poll','polls_share','user_id_shared','poll_id')->withPivot('user_id');
    }

    /**
     * do not want to return all these data to any user
     * @var array
     */
    protected $hidden = [
        'password'
    ];

}
