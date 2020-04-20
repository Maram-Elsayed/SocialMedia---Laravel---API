<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $table = 'reactions';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;

    protected $fillable = [
        'name'
    ];
    protected $hidden = [
        'updated_at'
    ];

    public function reactions(){
        return $this->hasMany('App\PostReaction');
    }

}
