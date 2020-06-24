<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'caption', 'cover_image', 'description'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }

    public function reactions(){
        return $this->hasMany('App\PostReaction');
    }

    public function postsNotifications(){
        return $this->hasMany('App\postNotification');
    }
}
