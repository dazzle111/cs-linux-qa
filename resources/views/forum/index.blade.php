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
		<div class="col-md-10" role="main">
		@foreach($discussions as $discussion)
			<div class="media">
			<div class="media-left">
			<a href="#">
				<img class="media=object img-circle" alt="64*64" src="{{ $discussion->user->avatar }}" width="64px" height="64px">
			</a>
			</div>
			<div class="media-body">
			<h4 class="media-heading">
				@if($discussion->is_top == 1)
				<a href="/discussions/{{ $discussion->id }}" class="index-title" style="color:#337ab7">
					{{ $discussion->title }}
					<i class="icon-top" alt="置顶" title="置顶">置顶</i>
	
				</a>
				
				@else
				<a href="/discussions/{{ $discussion->id }}" class="index-title" style="color:#337ab7">{{ $discussion->title }}</a>
				@endif
			</h4>
			
			<div class="media-conversation-meta">
			<span class="media-conversation-replies">
				<div class="center-block"><a href="/discussions/{{$discussion->id}}"> {{count($discussion->comments)}}</a></div>
				<i class="fa fa-envelope-square fa-3x " aria-hidden="true"></i>
			</span>
			</div>
			<div>
				<span class="username">
				{{ $discussion->user->name }}发表于{{$discussion->created_at->diffForHumans()}}
				</span>
			</div>
			<div class="item-excerpt col-md-10" style="">
				<span>{{$discussion->body}}</span>
			</div>
			</div>
			</div>
		@endforeach
		</div>
	</div>
</div>
<div class="col-md-8 pull-right">
<?php echo $discussions->render();?>
<div class="bottom">
	</dvi>
</div>

@stop
