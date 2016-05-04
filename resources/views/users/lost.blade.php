@extends('app')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3" role="main">
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
                {!!Form::open(['url' => '/user/lost'])!!}
                <div class="form-group">
                    {!! Form::label('name', '姓名:') !!}
                    {!! Form::text('text', null,['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('email', '邮箱:') !!}
                    {!! Form::email('email', null, ['class' => 'form-control']) !!}
                </div>
            
                {!! Form::submit('找回密码',['class' => 'btn btn-success form-control']) !!}
                {!!Form::close()!!}
            </div>
        </div>
    </div>
@stop
