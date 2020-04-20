<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $table = 'participants';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'userId','chatId'
    ];
    protected $hidden = [
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'userId');
    }

    public function chat(){
        return $this->belongsTo('App\Chat', 'chatId');
    }
}
