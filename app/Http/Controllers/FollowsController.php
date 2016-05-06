<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follow;
use DB;
use App\Http\Requests;

class FollowsController extends Controller
{
    
     public function follow(Request $request)
    {
    	
    	
        $follow = DB::table('follows')->where("discussion_id",$request->get("discussion_id"))->where("user_id",$request->get('user_id'))->get();
        
    	if( empty($follow) ){
    		Follow::create($request->all());
    		return \Response::json(array("status"=>"follow success"));

    	}
    	else 
    	{
    		DB::table('follows')->where("discussion_id",$request->get("discussion_id"))->where("user_id",$request->get('user_id'))->delete();

    		return \Response::json(array("status"=>"disfollow success"));

    	}
    }
}
