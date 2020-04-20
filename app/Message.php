<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'user_from','message'
    ];
    protected $hidden = [
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_from');
    }

    public function chat(){
        return $this->belongsTo('App\Chat', 'chatId');
    }
}
