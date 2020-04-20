<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostReaction extends Model
{
    protected $table = 'post_reactions';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'reactionId','userId','postId'
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

    public function reaction(){
        return $this->belongsTo('App\Reaction', 'reactionId');
    }
}
