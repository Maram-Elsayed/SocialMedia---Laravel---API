<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostNotification extends Model
{
    protected $table = 'postsnotifications';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'is_seen','is_read','message','postId','userId'
    ];
    protected $hidden = [
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'userId');
    }

    public function post(){
        return $this->belongsTo('App\Post', 'postId');
    }

    
}
