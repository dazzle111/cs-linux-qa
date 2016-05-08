@extends('app')

@section('content')

 @if(count($discussions) !== 0)
<div class="container">
    <div class="row">
        <div class="col-md-10" role="main">
            <p>相关搜索如下：</p>
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
                    <div class="center-block"><a href="/discussions/{{$discussion->id}}" > {{count($discussion->comments)}}</a></div>
                    <i class="fa fa-envelope-square fa-3x " aria-hidden="true"></i>
                </span>
                </div>
                <div>
                    <span class="username">
                    {{ $discussion->user->name }}发表于{{$discussion->created_at->diffForHumans()}}
                    </span>
                </div>
                <div class="item-excerpt col-md-10">
                    <span>{{$discussion->body}}</span>
                </div>
                </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
    @else
    <div class="container">
        <div class="row">
         <div class="col-md-10" role="main">
         <p>没有搜索到相关结果...<p>
         </div>
        </div>
    </div>
    @endif
<div class="col-md-8 pull-right">
<?php echo $discussions->render();?>
</div>
<div class="bottom" style="margin-bottom:200px">
</div>
@stop
