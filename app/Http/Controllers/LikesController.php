<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Like;
use App\Libraries\Notification;
use DB;

class LikesController extends Controller
{
    protected $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function likes(Request $request)
    {

    	$req = explode('_', $request->get('comment_id'));
    	$comment_id = $req[0];
    	$user_id = $req[1];
       
    	$result = DB::table('likes')->where('comment_id',$comment_id)->where('user_id',$user_id)->get();
    	if(empty($result))
    	{
    		$like = new Like;
	    	$like->comment_id = $comment_id;
	    	$like->user_id = $user_id;
	    	$like->save();
            $this->notification->WrapperNotify($target_id = $comment_id, $target_type = 'comment', $action = 'like', $sender_id = $user_id, "", 1);
	    	return \Response::json(array('status'=>'thumbs success'));
    	}
    	else
    	{
    		DB::table('likes')->where('comment_id',$comment_id)->where('user_id',$user_id)->delete();
    		return \Response::json(array('status' =>'disthumbs success'));
    	}
    	

    }
}
