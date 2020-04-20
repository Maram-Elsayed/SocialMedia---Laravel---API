<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $table = 'friend_requests';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'user_from'
    ];
    protected $hidden = [
        'user_to','updated_at'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_from');
    }

   
}
