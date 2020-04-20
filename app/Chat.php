<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'latest_message','name'
    ];
    protected $hidden = [
        'updated_at'
    ];

    public function message(){
        return $this->belongsTo('App\Message', 'latest_message');
    }

    public function participants(){
        return $this->hasMany('App\Participant');
    }
}
