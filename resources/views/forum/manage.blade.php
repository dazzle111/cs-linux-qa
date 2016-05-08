@extends('app')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-md-8 col-md-offset-2 main">
    @if(count($discussions) !== 0)
          <div class="col-sm-4 pull-right" id="manage-search">
          <form class="navbar-form navbar-left input-s-box m-t m-l-n-xs hidden-xs" role="search" action="/discussions/manage" method="get">
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
          <div>
          <h2 class="sub-header">管理列表</h2>

          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>id</th>
                  <th>发帖人</th>
                  <th>标题</th>
                  <th>发表时间</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                @foreach($discussions as $discussion)
                <tr>
                  <td><a href="/discussions/{{$discussion->id}}">{{$discussion->id}}</a></td>
                  <td>{{$discussion->user->name}}</td>
                  <td><a href="/discussions/{{$discussion->id}}">{{$discussion->title}}</a></td>
                  <td>{{$discussion->created_at}}</td>
                  <td class="operation" >
                    <button type="button" class="btn btn-sm btn-success" onclick="set_top({{$discussion->id}})">置顶</button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="drop_discussion({{$discussion->id}})">删除</button>
                  </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
          @else
          <div class="manage_search_tips">
             <h1>没有找到相关数据...</h1>
             <p><a class="btn btn-lg btn-success" href="/discussions/manage" role="button">点击返回</a></p>
         </div>
          @endif
        </div>
        <div class="col-md-8 pull-right">
          <?php echo $discussions->render();?>
        </div>
  <script>
    var token = document.querySelector('#token').getAttribute('value');

    function set_top(e){
      var top = confirm('确认置顶');
      if(top) {
            $.ajax({
             type: "post",
             url: "/discussions/top/",
             data: {discussion_id:e, _token:token},
             dataType: "json",
             success: function(data){
                if(data['status'] == 'success')
                {
                   window.location.reload();
                }
                else {
                  alert('置顶失败!');
                }
             }
         });

      }
    }

    function drop_discussion(e){

      var drop = confirm('确认删除');
      if(drop) {
        $.ajax({
             type: "delete",
             url: "/discussions/"+e,
             data: {discussion_id:e, user_id:{{Auth::user()->id}},_token:token},
             dataType: "json",
             success: function(data){
                if(data['status'] == 'success')
                {
                   window.location.reload();
                }
                else {
                  alert('删除失败!');
                }
             }
         });
      }
    }

  </script>
@stop