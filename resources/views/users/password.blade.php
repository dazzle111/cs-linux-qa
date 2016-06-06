@extends('app')

@section('content')
   @if(Auth::check())
    <div>
        <div class="row">
            <div class="col-md-4 col-md-offset-4" role="main" style="margin-top:48px">
                @if($errors->any())
                <ul class="list-group">
                @foreach($errors->all() as $error)
                    <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                @endforeach
                </ul>
                @endif
                @if(Session::has('user_login_failed'))
                    <div class="alert alert-danger" role="alert">
                        {{Session::get('user_login_failed')}}
                    </div>
                @endif
                {!!Form::open(['url' => '/user/password'])!!}
             
                <div class="form-group">
                    {!! Form::label('password', '原密码:') !!}
                    {!! Form::password('old_password', ['class' => 'form-control']) !!}
                </div>
                 <div class="form-group">
                    {!! Form::label('password', '密码:') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password_confirmation', '确认密码:') !!}
                    {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                </div>
             
                {!! Form::submit('更改密码',['class' => 'btn btn-success form-control']) !!}
                {!!Form::close()!!}
            </div>
        </div>
    </div>
    @else
    <div class="container">
    <div class="jumbotron">
        <h1>抱歉，你没有没有权限</h1>
        <p><a class="btn btn-lg btn-danger" href="/user/login" role="button">请先登录，再改密码</a></p>
      </div>
    </div>
       
    @endif
@stop
