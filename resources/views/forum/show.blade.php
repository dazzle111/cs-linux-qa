@extends('app')

@section('content')
<div class="jumbotron">
      <div class="container">
      		<div class="media">
				<div class="media-left">
				<a href="#">
					<img class="media-object img-circle" alt="64*64" src="{{ $discussion->user->avatar }}" width="64px" height="64px">
				</a>
				</div>
				<div class="media-body">
				<h4 class="media-heading">{{ $discussion->title }}
					@if(Auth::check() && Auth::user()->id == $discussion->user_id)
					<a class="btn btn-primary btn-lg pull-right" href="/discussions/{{$discussion->id}}/edit" role="button">修改帖子</a>
					@endif
				</h4>
				<h5 class="blog-post-meta">{{ $discussion->user->name }}
					<a href="#">发表于{{$discussion->created_at->diffForHumans()}}</a>
					@if($discussion->is_top == 1)
					<i class="icon-top" alt="置顶" title="置顶">置顶</i>
					@endif
				</h5>
				
				</div>
			</div>
      </div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-9" role="main" id="post">
			<div class="blog-post">
				{!! $html  !!}
            </div>
            <hr>
            <div>
            	<div class="EventPost-info"><a class="EventPost-user" href="#">
            		<i class="icon fa fa-fw fa-thumb-tack EventPost-icon"></i>
                    <span class="username">共 {{count($discussion->comments)}} 条回复</span></a>
                    </span>
                    @if($discussion->follow == 0)
                      <button class="btn btn-primary pull-right follow a" id="followq" role="button" style="margin-top: -6px;">关注问题</button>
                    @elseif($discussion->follow == 1)
                      <button class="btn btn-primary pull-right follow b" id="disfollowq" role="button" style="margin-top: -6px;">取消关注</button>
                     @endif
                </div>

            </div>
            @foreach($discussion->comments as $comment)

            <hr>
            <div class="media">
			<div class="media-left">
			<a href="#">
				<img class="media=object img-circle" alt="64*64" src="{{ $comment->user->avatar }}" width="64px" height="64px">
			</a>
			</div>
			<div class="media-body">
			<div>
			<span >{{ $comment->user->name}}</span>
			<time >回复于{{$comment->created_at}}</time>
			
			@if($comment->accepted)
			<span class="Badge Badge--group--4 pull-right" id="change_accept_{{$comment->id}}" style="background: #5fcf80; color:#fff">
                    <i >已采纳</i>
                    <i class="icon fa fa-fw fa-check Badge-icon" data-content="此回复靠谱!题主将它设为答案啦"></i>
            </span>
            @else
            <span class="Badge Badge--group--4 pull-right hidden hideopt" id="change_accept_{{$comment->id}}" style="background: #5fcf80; color:#fff">
                    <i class="icon fa fa-fw fa-check Badge-icon" data-content="此回复靠谱!题主将它设为答案啦"></i>
            </span>
         	@endif
			</div>
			{!! $comment->body !!}
			</div>
			@if(Auth::check())
			<div>
            	<ul class="btn-ul">
          			@if($comment->status == false)
            		<li class="item-like">
                    <ul class="participation__footer__like-list list-inline"> 
      				</ul>
       			<button id="forum-post-like-button" class="Button Button--link btn-self btn-fuck ">
           			 <span><i class="fa fa-thumbs-o-up thumbs" id="{{$comment->id}}_{{\Auth::user()->id}}">点赞({{count($comment->likes)}})</i></span>
        		</button>
             		</li>
             		@elseif($comment->status == true)
             	<li class="item-like">	
                    <ul class="participation__footer__like-list list-inline">
      				</ul>
       			<button id="forum-post-like-button" class="Button Button--link btn-self btn-fuck ">
           			 <span><i class="fa fa-thumbs-o-down thumbs" id="{{$comment->id}}_{{\Auth::user()->id}}">取消赞({{count($comment->likes)}})</i></span>
        		</button>
             		</li>
             		@endif
             		<!--<li class="item-reply">
                        <button class="Button Button--link comment-reply-button btn-self" data-username="snail" type="button" title="回复">
                            <i class="fa fa-reply-all">回复</i>
                        </button>
                    </li>-->
                    @if(Auth::user()->id == $discussion->user->id)
	                    @if($comment->accepted == 0)
	                    <li class="item-like btn-accept">
			       			<button id="forum-post-like-button btn-accept " class="Button Button--link btn-self btn-fuck">
			           			 <span><i class="fa fa-check-square-o accept opttype"  id="accept_{{$comment->id}}">采纳</i></span>
			        		</button>
	             		</li>

	             		@else
	             		 <li class="item-like btn-cancel">
			       			<button id="forum-post-like-button" class="Button Button--link btn-self btn-fuck">
			           			 <span><i class="fa fa-times cancel opttype"  id="accept_{{$comment->id}}">取消</i></span>
			        		</button>
	             		</li>
	             		@endif
             		@endif
             		@if(Auth::check() && Auth::user()->id == $comment->user_id)
					<li class="item-like">
						<button id="forum-post-like-button" class="Button Button--link btn-self">
           					 <a href="/discussion/{{$discussion->id}}/comment/{{$comment->id}}/edit"><i class="fa fa-edit">编辑</i></a>
        				</button>
        			</li>
        			@endif
            	</ul>
            </div>
            @endif
			</div>
			@endforeach
			
            <hr>
            <div class="media" v-for="comment in comments">
			<div class="media-left">
			<a href="#">
				<img class="media=object img-circle" alt="64*64" src="@{{ comment.avatar }}" width="64px" height="64px">
			</a>
			</div>
			<div class="media-body">
			
			<div>
			<span >@{{ comment.name}}</span>
			<time >回复于 @{{ comment.created_at }}</time>
			</div>
			@{{ comment.body }}
			</div>
			<div>
            	<ul class="btn-ul">

            		<li class="item-like">
                    <ul class="participation__footer__like-list list-inline"> 
      				</ul>
       			<button id="forum-post-like-button" class="Button Button--link btn-self btn-fuck">
           			 <i class="fa fa-thumbs-o-up"></i>点赞(0)
        		</button>
             		</li>
             		
                    <li class="item-like">
		       			<button id="forum-post-like-button" class="Button Button--link btn-self btn-fuck">
		           			 <i class="fa fa-check-square-o">采纳</i>
		        		</button>
             		</li>
             		<li class="item-like">
						<button id="forum-post-like-button" class="Button Button--link btn-self">
           					 <a href=""><i class="fa fa-edit">编辑</i></a>
        				</button>
        			</li>
            	</ul>
            </div>
            <hr>
			</div>
         	
            @if(Auth::check())
	        	{!!Form::open(['url' => '/comment','v-on:submit'=>'onSubmitForm'])!!}
	        	{!! Form::hidden('discussion_id',$discussion->id)!!}
	        	<div class="form-group">
                    {!! Form::textarea('body', null, ['class' => 'form-control','v-model'=>'newComment.body', 'placeholder'=>'支持Markdown语法','id'=>'content']) !!}
                </div> 
                <div>
                	{!! Form::submit('发表评论',['class' => 'btn btn-success pull-right']) !!}
                </div>

	            {!!Form::close()!!}
	            @else
	            	<a href="/user/login" class="btn btn-block btn-success">登录参与评论</a>
	            @endif
		</div>
	</div>
</div>
<div class="bottom">
</div>

<script>
	
	Date.prototype.Format = function(fmt)   
	{ //author: meizz   
	  var o = {   
	    "M+" : this.getMonth()+1,                 //月份   
	    "d+" : this.getDate(),                    //日   
	    "h+" : this.getHours(),                   //小时   
	    "m+" : this.getMinutes(),                 //分   
	    "s+" : this.getSeconds()+2,                 //秒   
	    "q+" : Math.floor((this.getMonth()+3)/3), //季度   
	    "S"  : this.getMilliseconds()             //毫秒   
	  };   
	  if(/(y+)/.test(fmt))   
	    fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));   
	  for(var k in o)   
	    if(new RegExp("("+ k +")").test(fmt))   
	  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));   
	  return fmt;   
	}  

	var time1 = new Date().Format("yyyy-MM-dd hh:mm:ss");
	Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

		new Vue({
			el:'#post',
			data:{
				comments:[],
				newComment:{
					name:'{{$user->name}}',
					avatar:'{{$user->avatar}}',
					created_at:time1,
					body:''
				},
				newPost:{
					discussion_id:'{{$discussion->id}}',
					user_id:'{{$user->id}}',
					body:''
				}
			},
			methods:{
				onSubmitForm:function(e){
					e.preventDefault();
					var comment = this.newComment;
					var post = this.newPost;
					post.body = comment.body;
					this.$http.post('/comment',post,function(){
						this.comments.push(comment);
					});
					this.newComment = {
						name:'{{$user->name}}',
						avatar:'{{$user->avatar}}',
						body:''
					};
				}
			}
		})
	
	
</script>
<script type="text/javascript">
    var inputer = $('#content');
    inputer.atwho({
        at: "@",
        data:"http://localhost:8000/user/name",
        insertTpl:'@${name}',
        displayTpl:'<li> ${name} </li>',
        startWithSpace:true,
        //data:['hello',],
        limit:7
    });
    inputer.on("inserted.atwho", function($li, query) {
        var token = $('#reply_form').find('input[name="_token"]').val();
        var data = {
            _token: token,
            name: query[0].textContent,
            status: "on"
        };
    });
    inputer.keydown(function (event) {
        if ( event.keyCode == 13 && (event.metaKey || event.ctrlKey)) {
            $('#publishButton').click();
        }
    });
</script>

<script>
	
	$(document).ready(function(){	
	var token = document.querySelector('#token').getAttribute('value');

    $('.opttype').click(function(){
        var tips='';
        if($("#"+this.id).hasClass("fa-check-square-o")){
            tips = "确认采纳?"
        }
        else
        {
            tips="取消采纳？"
        }
    	var accept = confirm(tips);
    	if(accept) {
	    	if($("#"+this.id).hasClass("fa-check-square-o"))
	    	{
	    		
	    		$("#"+this.id).removeClass("fa-check-square-o");
	    		$("#"+this.id).text('取消');
	    		$("#"+this.id).addClass("fa-times");
	    		$("#"+"change_"+this.id).removeClass("hidden");
	    	}
	    	else if($("#"+this.id).hasClass("fa-times"))
	    	{
	    		
	    		$("#"+this.id).removeClass("fa-times");
	    		$("#"+this.id).text("采纳");
	    		$("#"+this.id).addClass("fa-check-square-o");
	    		$("#"+"change_"+this.id).addClass("hidden");
	    	}
        $.ajax({
             type: "post",
             url: "/accept",
             data: {comment_id:this.id,discussion_id:{{$discussion->id}},_token:token},
             dataType: "json",
             success: function(data){      
             	window.location.reload();
             }
         });
    	}
    	
    });

    $('.thumbs').click(function(){
    	var id = $("#"+this.id).text();
    	var start = id.indexOf("(");
    	var end = id.indexOf(")");
    	var count = id.substr(start+1,end-start-1);
    	
     	if($("#"+this.id).hasClass("fa-thumbs-o-up")){
    		$("#"+this.id).removeClass("fa-thumbs-o-up");
    		$("#"+this.id).text('取消赞'+'('+(parseInt(count)+1)+')');
    		$("#"+this.id).addClass("fa-thumbs-o-down");
    	}
    	else if($("#"+this.id).hasClass("fa-thumbs-o-down"))
    	{
    		$("#"+this.id).removeClass("fa-thumbs-o-down");
    		$("#"+this.id).text('点赞'+'('+(parseInt(count)-1)+')');
    		$("#"+this.id).addClass("fa-thumbs-o-up");
    	}

         $.ajax({
             type: "post",
             url: "/thumbs",
             data: {comment_id:this.id,_token:token},
             dataType: "json",
             success: function(data){
                         console.log(data);
             }
         });
    });

    $('.follow').click(function(){

    	 if($('#'+this.id).hasClass('a')){
            $('#'+this.id).removeClass('a');
 		 	$('#'+this.id).text("取消关注");
            $('#'+this.id).addClass('b');
 		 } else if($("#"+this.id).hasClass('b')) {
            $('#'+this.id).removeClass('b');
 		 	$('#'+this.id).text("关注问题");
            $('#'+this.id).addClass('a');
 		 }
         $.ajax({
             type: "post",
             url: "/follow",
             data: {discussion_id:{{$discussion->id}},user_id:{{$user->id}}, _token:token},
             dataType: "json",
             success: function(data){
                         console.log(data);
             }
         });
    });

	});
</script>

@stop
