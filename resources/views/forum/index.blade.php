@extends('app')

@section('content')
<div class="jumbotron">
      <div class="container">
        <h1>西邮Linux兴趣小组QA平台
        	<a class="btn btn-primary btn-lg pull-right" href="/discussions/create" role="button">发布新问题</a>
        </h1>
      </div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-9" role="main">
		@foreach($discussions as $discussion)
			<div class="media">
			<div class="media-left">
			<a href="#">
				<img class="media=object img-circle" alt="64*64" src="{{ $discussion->user->avatar }}" width="64px" height="64px">
			</a>
			</div>
			<div class="media-body">
			<h4 class="media-heading"><a href="/discussions/{{ $discussion->id }}">{{ $discussion->title }}</a></h4>
			<div class="media-conversation-meta">
			<span class="media-conversation-replies">
				<a href="/discussions/{{$discussion->id}}"> {{count($discussion->comments)}}</a>
				回复
			</span>
			</div>
			{{ $discussion->user->name }}
			</div>
			</div>
		@endforeach
		</div>
	</div>
</div>
@stop
