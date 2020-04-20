<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $table = 'friends';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'user1','user2'
    ];

    protected $hidden = [
      'created_at','updated_at'
    ];

    public function user_1(){
        return $this->belongsTo('App\User', 'user1');
    }
    
    public function user_2(){
        return $this->belongsTo('App\User', 'user2');
    }
}
