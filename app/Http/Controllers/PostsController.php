<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discussion;
use App\Markdown\Markdown;
use EndaEditor;
use App\Comment;
use App\Libraries\Notification;
use App\User;
use DB;

use App\Http\Requests;

class PostsController extends Controller
{

    protected $markdown;
    protected $notification;

	public function __construct(Markdown $markdown, Notification $notification) 
	{
        $this->notification = $notification;
		$this->middleware('auth',['only' => ['create', 'store', 'edit', 'update']]);

        $this->markdown = $markdown;

	}
    
    public function index()
    {
    	$discussions = Discussion::orderBy('is_top','desc')->latest()->paginate(10);
        $notifys = '';
        if(\Auth::check()){
            $notifys = $this->notification->GetUserNotifyUnread(\Auth::user()->id);
        }

        return view('forum.index',compact('discussions','notifys'));
    }

    public function show($id)
    {

    	$discussion = Discussion::findOrFail($id);
        $html = $this->markdown->markdown($discussion->body);
        $notifys = "";
        foreach($discussion->comments as &$comment) {
            $comment->body = $this->markdown->markdown($comment->body);
        }

        if(\Auth::check()){
            $status = false;
            foreach ($discussion->comments as $comment) {
                # code...
                if(\Auth::check()) {
                    $com = Comment::findOrFail($comment->id);
                    $status = null;
                    foreach ($com->likes as $like) {
                        if($like->user_id == \Auth::user()->id)
                            $status = true;
                        else
                            $status = false;
                    }
                }
                $comment->status = $status;
            }

            $follow = DB::table('follows')->where('discussion_id',$discussion->id)->where('user_id',\Auth::user()->id)->get();
         
            if(empty($follow)) {
                $discussion->follow = 0;
            }
            else{
                $discussion->follow = 1;
            }

            $notifys = $this->notification->GetUserNotifyUnread(\Auth::user()->id);
            $user = \Auth::user();
        }
        else {
            $user = new User;
            $user->name = "游客";
            $user->id = "0";
            $user->avatar = "default-avatar.jpg";
        }
    	return view('forum.show',compact('discussion', 'html','notifys','user'));
    }

    public function store(Requests\StoreBlogPostRequest $request)
    {
    	$data = [
    		'user_id' => \Auth::user()->id,
    		'last_user_id' => \Auth::user()->id,
    	];

    	$discussion = Discussion::create(array_merge($request->all(),$data));

    	return redirect()->action('PostsController@show',['id' => $discussion->id]);
    }

    public function create()
    {
         $notifys = $this->notification->GetUserNotifyUnread(\Auth::user()->id);
    	return view('forum.create',compact('notifys'));
    }

    public function edit($id) {
        $discussion = Discussion::findOrFail($id);

        if(\Auth::user()->id !== $discussion->user_id) {
            return redirect('/');
        }

        return view('forum.edit', compact('discussion'));      
    }

    public function destroy(Request $request)
    {

        $user_id = $request->get('user_id');
        $discussion_id = $request->get('discussion_id');
        $user = User::find($user_id);

        if($user->permission == 1){
             $discussion =  Discussion::findOrFail($discussion_id);
             $discussion->delete();

             return \Response::json(array('status'=>'success'));
        }
        else 
        {
            return \Response::json(array('stutas'=>'failed'));
        }
       
    }   

    public function upload()
    {
        $data = EndaEditor::uploadImgFile('uploads');

        return json_encode($data);        
    }

    public function update(Requests\StoreBlogPostRequest $request, $id)
    {
        $discussion = Discussion::findOrFail($id);
        $discussion->update($request->all());

        return redirect()->action('PostsController@show', ['id'=>$discussion->id]);
    }

    public function search(Request $request)
    {
        $content = $request->get('content');
        $discussions = Discussion::where('title','like','%'.$content.'%')->orWhere('body','like','%'.$content.'%')->latest()->paginate(10);

        return view('forum.search',['discussions'=>$discussions]);
    }

    public function manage(Request $request)
    {

        $content = $request->get('content');
        $discussions = Discussion::where('title','like','%'.$content.'%')->latest()->paginate(10);
        $notifys = $this->notification->GetUserNotifyUnread(\Auth::user()->id);
        return view('forum.manage',compact('discussions','notifys'));
    }

    public function top(Request $request) 
    {
        $discussion_id = $request->get('discussion_id');

        $discussion = Discussion::findOrFail($discussion_id);
        $discussion->is_top = 1;
        $discussion->save();

        $this->notification->WrapperNotify($target_id = $discussion_id, $target_type = 'discussion', $action = 'top', $sender_id = \Auth::user()->id, "", 1);
        return \Response::json(array("status" => "success"));
    }
    
    public function test()
    {
        $this->notification->test();
    }
}
