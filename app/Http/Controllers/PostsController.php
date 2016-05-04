<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discussion;
use App\Markdown\Markdown;
use EndaEditor;
use App\Comment;

use App\Http\Requests;

class PostsController extends Controller
{

    protected $markdown;

	public function __construct(Markdown $markdown) 
	{
		$this->middleware('auth',['only' => ['create', 'store', 'edit', 'update']]);

        $this->markdown = $markdown;

	}
    public function index()
    {
    	$discussions = Discussion::latest()->paginate(10);
        return view('forum.index',compact('discussions'));
    }

    public function show($id)
    {
    	$discussion = Discussion::findOrFail($id);
        $html = $this->markdown->markdown($discussion->body);
        foreach($discussion->comments as &$comment) {
            $comment->body = $this->markdown->markdown($comment->body);
        }
        
        $comments = $discussion->comments;

        $result = array();
        $result1 = array();
        foreach($comments as $comment)
        {
            $com = Comment::findOrFail($comment->id);
            $count = count($com->likes);
            $status = null;
            foreach ($com->likes as $like) {
                if($like->id == \Auth::user()->id)
                    $status = true;
                else
                    $status = false;
            }
            //dd(array('count'=>$count, 'status'=>$status));
           array_push($result,array('count'=>$count, 'status' => $status));
        }
    	return view('forum.show',compact('discussion', 'html','result'));
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
    	return view('forum.create');
    }

    public function edit($id) {
        $discussion = Discussion::findOrFail($id);

        if(\Auth::user()->id !== $discussion->user_id) {
            return redirect('/');
        }

        return view('forum.edit', compact('discussion'));      
    }

    public function destroy()
    {}

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
}
