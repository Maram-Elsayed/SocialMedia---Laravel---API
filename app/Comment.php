<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'comment', 'image','postId','userId'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'userId');
    }

    public function post(){
        return $this->belongsTo('App\Post', 'postId');
    }
}
