<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        "post_id",
        "user_id",
        "content"
    ];
    function post(){
        return $this->belongsTo(Post::class,"post_id");
    }
}
