<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    
    protected $fillable = [
        'name', 'email', 'password', 'profile_picture','gender','birthday'
    ];

  
    protected $hidden = [
        'password', 'remember_token','email_verified_at','status','created_at','updated_at'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function friend_request(){
        return $this->hasMany('App\FriendRequest');
    }

    public function friends(){
        return $this->hasMany('App\Friend');
    }
    
    public function posts(){
        return $this->hasMany('App\Post');
    }

    public function reactions(){
        return $this->hasMany('App\PostReaction');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }
}
