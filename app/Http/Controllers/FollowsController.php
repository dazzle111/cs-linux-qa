<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follow;
use DB;
use App\Http\Requests;
use App\Libraries\Notification;

class FollowsController extends Controller
{
    protected $notification;
    
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

     public function follow(Request $request)
    {

    	$user_id = $request->get('user_id');
        $discussion_id = $request->get('discussion_id');
    	
        $follow = DB::table('follows')->where("discussion_id",$discussion_id)->where("user_id",$user_id)->get();
        
    	if( empty($follow) ){
    		Follow::create($request->all());

            $this->notification->WrapperNotify($target_id=$discussion_id, $target_type = 'discussion', $action = 'follow', $sender_id = $user_id, "", 1);

    		return \Response::json(array("status"=>"follow success"));

    	}
    	else 
    	{
    		DB::table('follows')->where("discussion_id",$request->get("discussion_id"))->where("user_id",$request->get('user_id'))->delete();

    		return \Response::json(array("status"=>"disfollow success"));

    	}
    }
}
