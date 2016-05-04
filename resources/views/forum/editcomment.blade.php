@extends('app')

@section('content')
  <div class=“container”>
          <div class="row">
              <div class="col-md-8 col-md-offset-2" role="main">

                {!!Form::model($comment,['method'=>'PATCH', 'url'=> '/comments/'.$discussion.'/comment/'.$comment->id])!!}
                {!! Form::hidden('discussion_id',$discussion)!!}
                 <div class="form-group">
                    <div class="editor">
                      {!! Form::textarea('body', null, ['class' => 'form-control','id'=>'myEditor']) !!}
                    </div>
                   </div>
                   <div>
                    {!! Form::submit('更新评论',['class' => 'btn btn-primary pull-right']) !!}
                   </div>
                  {!!Form::close()!!}

              </div>
          </div>
  </div>

@stop