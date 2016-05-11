<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
	protected $table = "notifys";
     
    public function user()
    {
        //默认外键是user_id,如果有其他名字的话，可通过第二个参数传递
        return $this->belongsTo(User::class);   //discussion->user
    }
    public function usernotifys()
    {
        return $this->hasMany(UserNotify::class);
    }
}
