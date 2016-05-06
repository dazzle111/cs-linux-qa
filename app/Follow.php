<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = ['discussion_id','user_id'];
    
    public function user()
    {
        //默认外键是user_id,如果有其他名字的话，可通过第二个参数传递
        return $this->belongsTo(User::class);   //user->user
    }

     public function discussion()
    {
        //默认外键是user_id,如果有其他名字的话，可通过第二个参数传递
        return $this->belongsTo(Discussion::class);   //discussion->user
    }


}
