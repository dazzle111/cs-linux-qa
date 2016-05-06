<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Like;
use DB;

class LikesController extends Controller
{
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

	    	return \Response::json(array('status'=>'thumbs success'));
    	}
    	else
    	{
    		DB::table('likes')->where('comment_id',$comment_id)->where('user_id',$user_id)->delete();
    		return \Response::json(array('status' =>'disthumbs success'));
    	}
    	

    }
}
