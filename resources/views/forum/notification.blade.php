@extends('app')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-8 col-md-offset-2 main">
       <div class="btn-group btn-notification">
          @if($flag == 'all')
          <button class="btn" style="outline:none"><a href="/notification?flag=all" class="a-list">所有动态({{count($alls)}})</a></button>
          @elseif($flag == 'new')
          <button class="btn" style="outline:none"><a href="/notification?flag=new" class="a-list">我的消息({{count($news)}})</a></button>
          @elseif($flag == 'follow')
          <button class="btn" style="outline:none"><a href="/notification?flag=follow" class="a-list">我的关注({{count($follows)}})</a></button>
          @elseif($flag == 'comment')
          <button class="btn" style="outline:none"><a href="/notification?flag=comment" class="a-list">我的评论({{count($comments)}})</a></button>
          @endif
      </div>
      <div class="row">
        <div class="col-md-9">
            <hr>
            <div class="container">
              @if($flag == 'new')
                @if(count($news) == 0)
                <p>暂无最新消息...</p>
                @else
                @foreach($news as $new)
                @if($new->is_read ==0)
                <p class="lead" style="font-size:15px"><a>{{$new->username}} </a> {{$new->action}} <a href="/discussions/{{$new->discussion_id}}" id="{{$new->id}}" class="mymessage"> {{$new->title}} </a> {{$new->updated_at->diffForHumans()}} <i class="new"> new</i></p>
                @else
                <p class="lead" style="font-size:15px"><a>{{$new->username}} </a> {{$new->action}} <a href="/discussions/{{$new->discussion_id}}" id="{{$new->id}}" class="mymessage"> {{$new->title}} </a> {{$new->updated_at->diffForHumans()}}</p>
                @endif
                @endforeach
              @endif
              @elseif($flag == 'comment')
               @foreach($comments as $comment)
              <p class="lead" style="font-size:15px"><a>{{$comment->username}} </a> {{$comment->action}} <a href="/discussions/{{$comment->discussion_id}}" id="{{$comment->id}}" class="mymessage"> {{$comment->title}} </a> {{$comment->updated_at}}</p>
              @endforeach
              @elseif($flag == 'follow')
               @foreach($follows as $follow)
              <p class="lead" style="font-size:15px"><a>{{$follow->username}} </a> {{$follow->action}} <a href="/discussions/{{$follow->discussion_id}}" id="{{$follow->id}}" class="mymessage"> {{$follow->title}} </a> {{$follow->updated_at}}</p>
              @endforeach
              @elseif($flag == 'all')
               @foreach($alls as $all)
              <p class="lead" style="font-size:15px"><a>{{$all->username}} </a> {{$all->action}} <a href="/discussions/{{$all->discussion_id}}" id="{{$all->id}}" class="mymessage"> {{$all->title}} </a> {{$all->updated_at}}</p>
              @endforeach
              @endif
            </div>
            <hr>
        </div>
        <div class="col-md-3">
           <div class="bs-docs-example">
              <ul class="nav nav-pills nav-stacked">
                  <li class="active"><a href="#">导航栏</a></li>
                  <li><a href="/notification?flag=all">所有动态({{count($alls)}})</a></li>
                  <li><a href="/notification?flag=new">我的消息({{count($news)}})</a></li>
                  <li><a href="/notification?flag=comment">我的评论({{count($comments)}})</a></li>
                  <li><a href="/notification?flag=follow">我的关注({{count($follows)}})</a></li>
              </ul>
          </div>
        </div>
        </div>
    <div class="bottom">
    </div>
</div>



<script>
    var token = document.querySelector('#token').getAttribute('value');
    $('.mymessage').click(function(e){

      e.preventDefault();

      $.ajax({
             type: "post",
             url: "/notification/mymessage",
             data: {notify_id:this.id, _token:token},
             dataType: "json",
             success: function(data){
                         console.log(data);
             }
         });
    
      location.href= $(this).attr('href');
    });
</script>
 
@stop