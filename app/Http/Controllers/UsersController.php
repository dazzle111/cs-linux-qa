<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Notification;

use App\User;
use App\Http\Requests;
use DB;
use Image;

class UsersController extends Controller
{
    protected $notification;
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }
    public function register()
    {
        return view('users.register');
    }

    public function store(Requests\UserRegisterRequest $request)
    {
    	User::create(array_merge($request->all(),['avatar' => '/images/default-avatar.jpg']));
    		//send mail
    	return redirect('/');
    }

    public function login()
    {
        if(\Auth::check())
            return redirect()->action('PostsController@index');
    	return view('users.login');
    }

    public function signin(Requests\UserLoginRequest $request)
    {
    	if(\Auth::attempt([
    			'email' => $request->get('email'),
    			'password' => $request->get('password')
    		])) {
    		return redirect('/');
    	}

    	\Session::flash('user_login_failed', '密码不正确或邮箱没验证');
    	return redirect('/user/login')->withInput();
    }

    public function avatar()
    {
        $notifys = $this->notification->GetUserNotifyUnread(\Auth::user()->id);
    	return view('users.avatar',compact('notifys'));
    }

    public function changeAvatar(Request $request)
    {
    	
    	$file = $request->file('avatar');
    	$input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        );
        $validator = \Validator::make($input, $rules);
        if ( $validator->fails() ) {
            return \Response::json([
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            ]);

        }

    	$destinationPath = 'uploads/';
        $filename = \Auth::user()->id.'_'.time().$file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        Image::make($destinationPath.$filename)->fit(200)->save();
        $user = User::find(\Auth::user()->id);
        $user->avatar = '/'.$destinationPath.$filename;
        $user->save();

        return \Response::json([
        		'success' => true,
        		'avatar' => asset($destinationPath.$filename),
        		'image'	=> $destinationPath.$filename
        	]);
    }

    public function cropAvatar(Request $request)
    {
    	$photo = $request->get('photo');
    	$width = (int) $request->get('w');
    	$height = (int) $request->get('h');
    	$x = (int) $request->get('x');
    	$y = (int) $request->get('y');

    	Image::make($photo)->crop($width, $height, $x, $y)->save();

    	$user = \Auth::user();
    	$user->avatar = asset($photo);
    	$user->save();

    	return redirect('/user/avatar');
    }
    public function password()
    {
        return view('users.password');
    }

    public function changePassword(Requests\UserPasswordRequest $request)
    {
        $user = User::findOrFail(\Auth::user()->id);
        $user->password = $request->get('password');
        $user->save();
        return redirect('/');
    }

    public function lost()
    {
        return view('users.lost');
    }


    public function logout()
    {
    	\Auth::logout();
    	return redirect('/');
    }

    public function username()
    {
        $name = DB::table('users')->select('name')->get();
        return \Response::json($name);
    }
}
