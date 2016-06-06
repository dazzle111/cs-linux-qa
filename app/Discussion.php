<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected $fillable = ['title','body','user_id','last_user_id','is_top'];

    public function user()
    {
        //默认外键是user_id,如果有其他名字的话，可通过第二个参数传递
        return $this->belongsTo(User::class);   //discussion->user
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

     public function follows()
    {
        return $this->hasMany(Follow::class);
    }

}
