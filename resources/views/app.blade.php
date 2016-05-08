<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>西邮Linux兴趣小组QA平台</title>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/font-awesome.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/jquery.Jcrop.css">
    <script src="/js/jquery-2.2.3.min.js"></script>   
    <script src="/js/jquery.Jcrop.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery.form.js"></script>
    <script src="/js/vue.min.js"></script>
    <script src="/js/vue-resource.min.js"></script>
    <script src="/js/jquery.atwho.min.js"></script>
    <script src="/js/jquery.caret.min.js"></script>
    <!--<script src="//cdn.bootcss.com/pusher/3.0.0/pusher.min.js"></script>-->
    <meta id="token" name="token" value="{{ csrf_token()}}">
<head>
<body>
   <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">QA平台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/">首页</a></li>
          </ul>
          <div class="col-md-8">
          <form class="navbar-form navbar-left input-s-box m-t m-l-n-xs hidden-xs" role="search" action="/discussions/search" method="get">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-btn">
                          <button type="submit" id="bt_search" class="btn btn-sm bg-white btn-icon rounded"><i class="fa fa-search"></i></button>
                        </span>
                        <input name="content" type="text" id="search_inf" class="form-control input-sm no-border rounded" placeholder="搜索相关问题...">
                    </div>
                </div>
            </form>
          </div>
          <ul class="nav navbar-nav navbar-right">
            @if(Auth::check())
              <li><a id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true">{{Auth::user()->name }}</a>
              <ul class="dropdown-menu" aria-labelledby="dLabel">
                <li><a href="/user/avatar"> <i class="fa fa-user"></i> 更换头像</a></li>
                <li><a href="/user/password"> <i class="fa fa-cog"></i> 更换密码</a></li>
                @if(Auth::user()->permission == 1)
                  <li><a href="/discussions/manage"> <i class="fa fa-file-text"></i> 管理帖子</a></li>
                @endif
                <li><a href="#"> <i class="fa fa-envelope"></i> 消息通知<span class="badge bg-danger" id="newmessage" style="background:#f05050">1</span></a></li>
                <li><a href="#"> <i class="fa fa-heart"></i> 特别感谢</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="/logout"> <i class="fa fa-sign-out"></i>退出登录</a></li>
              </ul>
            </li>
              <li><img src="{{Auth::user()->avatar}}" class="img-circle" width="50" alt="">
            @else
            <li><a href="/user/login">登录</a></li>
            <li><a href="/user/register">注册</a></li>
            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
@yield('content')
<!--<script src="//cdn.bootcss.com/jquery/3.0.0-alpha1/jquery.min.js"></script>-->
<!--<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
</body>
</html>
