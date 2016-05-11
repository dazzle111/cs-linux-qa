<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Http\Requests;
use DB;
use App\Libraries\Notification;

class CommentsController extends Controller
{
    protected $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }
    
    public function store(Requests\PostCommentRequest $request)
    {

    	Comment::create(array_merge($request->all(), ['user_id' => \Auth::user()->id]));

        $discussion_id = $request->get('discussion_id');
        $user_id = $request->get('user_id');

        $this->notification->WrapperNotify($target_id = $discussion_id, $target_type = 'discussion', $action = 'comment', $sender_id = $user_id, "", 1);

    	return redirect()->action('PostsController@show', ['id' => $request->get('discussion_id')]);
    }

    public function editComment($id,$id1)
    {   
        $discussion = $id;
        $comment = Comment::findOrFail($id1);

        return view('forum.editcomment',compact('comment','discussion'));
    }

    public function changeComment(Requests\PostCommentRequest $request, $id, $id1)
    {
        $comment = Comment::findOrFail($id1);
        $comment->update($request->all());

        return redirect()->action('PostsController@show', ['id' => $id]);        
    }

    public function check(Request $request) 
    {
    	$result = array("status" => "success");
    	return  \Response::json($result);
    }

    public function accept(Request $request) 
    {

        $result = explode('_',$request->get('comment_id'));
        $comment_id = $result[1];
        
        $old_comment = Comment::where('discussion_id',$request->get('discussion_id'))->where('accepted',1)->first();
        $comment = Comment::find($comment_id);

        //获取问题所有者id
        $user = DB::table('discussions')->select('user_id')->where('id',$request->get('discussion_id'))->first();

        if(empty($old_comment)) {   //未采纳任何回复
            $comment->accepted = 1;
            $comment->save();
            $status = "accepted success";


            $this->notification->WrapperNotify($target_id = $comment_id, $target_type = 'comment', $action = 'accept', $sender_id = $user->user_id, "", 1);
            
        }
        else if($old_comment->id == $comment_id){   //取消采纳
            $old_comment->accepted = 0;
            $old_comment->save();
            $status = "disaccepted success";
            
        }
        else {          //采纳新的回复
            $old_comment->accepted = 0;
            $old_comment->save();
            $comment->accepted = 1;
            $comment->save();
            $status = "change accepted success";
        
            $this->notification->WrapperNotify($target_id = $comment_id, $target_type = 'comment', $action = 'accept', $sender_id = $user->user_id, "", 1);

            
        }
        return \Response::json(array('status'=>$status));
    }

    public function thumbs(Request $request)
    {
    	$cancel = Comment::findOrFail($request->get('comment_id'));
    	if($cancel->accepted == 1) {
    		$cancel->accepted = 0;
    		$cancel->save();

    	return \Response::json(array("status"=>"success","comment_id"=>$request->get('comment_id')));
    	}
    	else 
    		return \Response::json(array("status"=>"failed","comment_id"=>$request->get('comment_id')));
    }
}
