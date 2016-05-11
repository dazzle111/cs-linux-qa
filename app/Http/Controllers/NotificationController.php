<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Libraries\Notification;
use DB;
use App\UserNotify;

class NotificationController extends Controller
{
	protected $notification;
	public function __construct(Notification $notification)
	{
		$this->notification = $notification;
	}
    public function index(Request $request)
    {
        
        $flag = $request->get('flag');
       
   		
   		{
        //我的消息
        $notifys = $this->notification->GetUserNotifyUnread(\Auth::user()->id);
   			$news = $this->notification->GetUserNotifyAll(\Auth::user()->id);
   		}
   		
   		{
        //我的回复
   			$comments = DB::table('comments')->where('user_id',\Auth::user()->id)->get();
   			foreach ($comments as &$comment) 
   			{
   				# code...
   				$discussion = DB::table('discussions')->where('id',$comment->discussion_id)->get();
   				$comment->title = $discussion[0]->title;
   				$comment->username = '我';
   				$comment->action = '回复了';
   				$comment->discussion_id = $discussion[0]->id;
   			}
   			array_multisort($comments,SORT_DESC);
   		}
   	
   		{
   			//我的关注
   			$follows = DB::table('follows')->where('user_id',\Auth::user()->id)->get();
   			foreach ($follows as $follow) 
   			{
   				# code...
   				$discussion = DB::table('discussions')->where('id',$follow->discussion_id)->get();

   				$follow->title = $discussion[0]->title;
   				$follow->username = '我';
   				$follow->action = '关注了';
   				$follow->discussion_id = $discussion[0]->id;

   			}
   			array_multisort($follows,SORT_DESC);
   		}
   		
   		{
        //全部动态
   			$alls = array_merge($follows,$comments);
       
   			array_multisort($alls,SORT_DESC);
   		}

        return view('forum.notification',compact('flag','notifys','comments','follows','alls','news'));
    }
    public function read(Request $request)
    {
    	$notify_id = $request->get('notify_id');
    	$notify = UserNotify::findOrFail($notify_id);
    	$notify->is_read = 1;
    	$notify->save();
    }
}
