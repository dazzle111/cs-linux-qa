<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Like;
use App\Comment;

class LikesController extends Controller
{
    public function likes(Request $Request, $id)
    {
    	Like::create(array_merge($request->get('comment_id'), ['user_id' => \Auth::user()->id]));

    	return redirect()->action('PostsController@show', ['id' => $request->get('discussion_id')]);
    }
}
