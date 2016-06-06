<?php
namespace App\Libraries;
use App\Notify;
use App\UserNotify;
use DB;
class Notification 
{
	public function test()
	{
		 $notification = Notify::all();
	}

	#type:1.消息,2.公告 action:comment,follow,like,accept
	public function WrapperNotify($target_id,$target_type,$action,$sender_id,$content,$type)
	{

		$result = $this->CreateNotify($target_id,$target_type,$action,$sender_id,$content,$type);
		
		#获取新插入的notify
		if($result == true && $type == 1)
		{
			$new_notify = Notify::where('sender_id',$sender_id)->where('target_id',$target_id)->where('target_type',$target_type)->where('action',$action)->latest()->first();
		    
			$users = null;
			
			#获取与notify相关user
			switch ($action) {
				case 'comment':
					$follow_users = DB::table('follows')->select('user_id')->where('discussion_id',$target_id)->get();
					$owner = DB::table('discussions')->select('user_id')->where('id',$target_id)->get();
					$users = array_merge($follow_users,$owner);
					break;
				case 'follow':
					$users = DB::table('discussions')->select('user_id')->where('id',$target_id)->get();
					break;
				case 'like':
					$users = DB::table('comments')->select('user_id')->where('id',$target_id)->get();
					break;
				case 'accept':
					$users = DB::table('comments')->select('user_id')->where('id',$target_id)->get();
					break;
				case 'top':
					$users = DB::table('discussions')->select('user_id')->where('id',$target_id)->get();
					break;
				
			}
			
			if($this->CreateUserNotify($users,$new_notify->id))	//$users:array()
			{
				return true;
			}	
		}	
	}

    public  function CreateNotify($target_id,$target_type="",$action="",$sender_id,$content="",$type=1)
    {
        $notify = new Notify;

        //检查消息是否已存在
        $check = Notify::where('sender_id',$sender_id)->where('target_id',$target_id)->where('target_type',$target_type)->where('action',$action)->get();
   		
        //第一次评论或者在同一个问题下再次评论
        if((count($check) == 0) || (count($check) >= 1 && $action = "comment")) 
        {
	        $notify->content = $content;
	        $notify->type = $type;
	        $notify->target_id = $target_id;
	        $notify->target_type = $target_type;
	        $notify->action = $action;
	        $notify->sender_id = $sender_id;

	        $notify->save();
    	}
       
        return true;
    }

    public function CreateUserNotify($users,$notify_id)
    {
    	$count = count($users);
    	
    	$check = UserNotify::where('notify_id',$notify_id)->where('user_id',$users[0]->user_id)->get();

    	if(count($check) == 0){
    		for($i = 0; $i < $count; $i++){
		    	$usernotify = new UserNotify;
		    	$usernotify->is_read = 0;
		    	$usernotify->user_id = $users[$i]->user_id;
		    	$usernotify->notify_id = $notify_id;
		    	$usernotify->save();
    		}
    	}


    	return true;
    }

    public function GetUserNotifyAll($user_id)
    {
    	$usernotifys = UserNotify::where('user_id',$user_id)->latest()->get();
    	return $this->translate($usernotifys);
    }

    public function GetUserNotifyUnread($user_id)
    {
    	$usernotifys = UserNotify::where('user_id',$user_id)->where('is_read',0)->latest()->get();
    	
    	return $this->translate($usernotifys);
    }

    public function SetNotifyRead($id)
    {
    	$notify = UserNotify::findOrFail($id);
    	$notify->is_read = 1;
    	$notify->save();

    	return true;
    }

    public function translate($requests)
    {

    	foreach ($requests as &$request) 
    	{
    		# code...
    		//获取相关用户信息
    		$user = DB::table('users')->where('id',$request->notify->sender_id)->first();
    		
    		$request->username = $user->name;	//设置姓名
    		$request->avatar = $user->avatar;	//设置头像

    		#区分目标是跟问题相关（置顶，关注，回复）还是跟回复相关（点赞，采纳）
    		if($request->notify->target_type == 'discussion') 
    		{
    			$discussion = DB::table('discussions')->where('id',$request->notify->target_id)->first();
    			$request->title = $discussion->title;
    			$request->discussion_id = $discussion->id;
    		}
    		else if($request->notify->target_type == 'comment') 
    		{
    			$comment = DB::table('comments')->where('id',$request->notify->target_id)->first();
    			$discussion = DB::table('discussions')->where('id',$comment->discussion_id)->first();
    			$request->title = $discussion->title;
    			$request->discussion_id = $discussion->id;
    			$request->body = $comment->body;
    		}

    		#翻译动作
    		switch ($request->notify->action) {
    			case 'comment':
    				$request->action = "评论了";
    				break;
    			case 'follow':
    				$request->action = "关注了";
    				break;
    			case 'like':
    				$request->action = "赞同了";
    				break;
    			case 'accept':
    				$request->action = "采纳了";
    				break;
    			case 'top':
    				$request->action = "置顶了";
    				break;
    			default:
    				# code...
    				break;
    		}
    		
    	}

    	return $requests;
    }
}
?>
