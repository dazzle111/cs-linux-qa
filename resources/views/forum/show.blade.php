@extends('app')

@section('content')
<div class="jumbotron">
      <div class="container">
      		<div class="media">
				<div class="media-left">
				<a href="#">
					<img class="media=object img-circle" alt="64*64" src="{{ $discussion->user->avatar }}" width="64px" height="64px">
				</a>
				</div>
				<div class="media-body">
				<h4 class="media-heading">{{ $discussion->title }}
					@if(Auth::check() && Auth::user()->id == $discussion->user_id)
					<a class="btn btn-primary btn-lg pull-right" href="/discussions/{{$discussion->id}}/edit" role="button">修改帖子</a>
					@endif
				</h4>
				<h5 class="blog-post-meta">{{ $discussion->user->name }}<a href="#">发表于{{$discussion->created_at->diffForHumans()}}</a></h5>
				
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
			</div>
			{!! $comment->body !!}
			</div>
			</div>
            @endforeach
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
			</div>
            <hr>
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
<script>
	Date.prototype.Format = function(fmt)   
	{ //author: meizz   
	  var o = {   
	    "M+" : this.getMonth()+1,                 //月份   
	    "d+" : this.getDate(),                    //日   
	    "h+" : this.getHours(),                   //小时   
	    "m+" : this.getMinutes(),                 //分   
	    "s+" : this.getSeconds(),                 //秒   
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
				name:'{{Auth::user()->name}}',
				avatar:'{{Auth::user()->avatar}}',
				created_at:time1,
				body:''
			},
			newPost:{
				discussion_id:'{{$discussion->id}}',
				user_id:'{{Auth::user()->id}}',
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
					name:'{{Auth::user()->name}}',
					avatar:'{{Auth::user()->avatar}}',
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
        data:['sodasix',]
    });
    inputer.on("inserted.atwho", function($li, query) {
        var token = $('#reply_form').find('input[name="_token"]').val();
        var data = {
            _token: token,
            name: query[0].textContent,
            conversation_id: $('#conversation_id').val(),
            status: "on"
        };
       $.post(
                "/comment/api/create",data,function(response){
                    if(response.status === 'success'){
                    }
                },
                "json");

    });
    inputer.keydown(function (event) {
        if ( event.keyCode == 13 && (event.metaKey || event.ctrlKey)) {
            $('#publishButton').click();
        }
    });
</script>
<div class="atwho-container">
	<div id="atwho-ground-content">
		<div class="atwho-view" id="at-view-64" style="display: none; top: 1483px; left: 159.015px;">
			<ul class="atwho-view-ul">
				<li class="cur">sodasix</li>
			</ul>
		</div>
	</div>
</div>
@stop
