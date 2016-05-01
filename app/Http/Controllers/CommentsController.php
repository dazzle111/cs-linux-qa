<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Requests;

class CommentsController extends Controller
{
    //
    public function store(Requests\PostCommentRequest $request)
    {
    	Comment::create(array_merge($request->all(), ['user_id' => \Auth::user()->id]));

    	return redirect()->action('PostsController@show', ['id' => $request->get('discussion_id')]);
    }
}
