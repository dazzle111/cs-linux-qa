<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

	protected $fillable = ['body', 'user_id', 'discussion_id'];
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function discussion()
    {
    	return $this->belongsTo(Discussion::class);
    }

    public function likes()
    {
        return $this->hasMany(like::class);
    }

    public function setCreatedAtAttribute($date)
    {
    	$this->attributes['created_at'] = Carbon::now();
    }
}
