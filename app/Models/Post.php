<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "body"
    ];
    function user(){
        return $this->belongsTo(User::class,"user_id");
    }
    function comment(){
        return $this->hasMany(Comment::class);
    }
    function like(){
        return $this->hasMany(Like::class);
    }
    
}

