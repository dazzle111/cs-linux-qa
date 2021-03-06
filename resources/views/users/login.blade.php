@extends('app')

@section('content')
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
                {!!Form::open(['url' => '/user/login'])!!}
             
                <div class="form-group">
                    {!! Form::label('email', '邮箱:') !!}
                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('password', '密码:') !!}
                    {!! Form::password('password', ['class' => 'form-control']) !!}
                </div>
             
                {!! Form::submit('登录',['class' => 'btn btn-success form-control']) !!}
                {!!Form::close()!!}
                <div align="center"><a href="/user/lost"><p>忘记密码？</p></a></div>
            </div>
        </div>
    </div>
@stop
