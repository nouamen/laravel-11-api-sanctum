<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        "post_id",
        "like_id"
    ];
    function post(){
        return $this->belongsTo(Post::class,"post_id");
    }
}
