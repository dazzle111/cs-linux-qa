@extends('app')
@section('content')
<div class="container">
	<div class="col-md-3">
<!-- Stack the columns on mobile by making one full-width and the other half-width -->
	<div class="text-center">
	
  		<a href="/user/avatar" class="thumb-lg">
	        <img src="{{\Auth::user()->avatar}}" class="img-circle img-wrapper">
	    </a>

	     <div class="h3 profile-n"><h3 class="text-muted" id="profile-name" style="color:black">{{Auth::user()->name}}</h3>
        	 <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm bg-white btn-icon" style="background-color:#fff;"><i class="fa fa-pencil-square-o" style=""> </i></button>
        </div>
        <p style="border-color:#fff;background-color:#fff" id="my-motto" disabled>{{Auth::user()->motto}}</p>
  	</div>
                  <!-- Button trigger modal -->
					<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title" id="myModalLabel">座右铭</h4>
			      </div>
			      <div class="modal-body">
			      	<div>
			        <textarea class="form-control" style="height:200px;" id="motto">{{Auth::user()->motto}}</textarea>
			    	</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			        <button type="button" class="btn btn-success" id="submit-motto">保存</button>
			      </div>
			    </div>
			  </div>
			</div>
	</div>
  <div class="col-md-9">
  	<div class="col-md-10">
  		<section class="panel panel-default panel-top">
	        <header class="panel-heading">
	            <strong>个人信息</strong>
	        </header>
	        <div class="panel-body">
	            <div class="form-group" style="padding-left: 15px;padding-right: 15px; position:relative; min-height: 1px">
	                <label>昵称</label>
	                <input id="name" type="text" class="form-control" value="{{\Auth::user()->name}}" disabled>
	            </div>
	            <div class="form-group" style="padding-top: 1px;padding-left: 15px;padding-right: 15px; position:relative; min-height: 1px;">
	                <label>邮箱</label>
	                <input id="email" class="form-control" value="{{\Auth::user()->email}}" disabled>
	            </div>
	           
	            <div class="form-group" style="padding-left: 15px;padding-right: 15px; position:relative; min-height: 1px">
                    <div class="row">
                        <div class="col-md-6" style="padding-right:0px width:10%">
                        	<label>级别</label>
                       		<input type="text" class="form-control" id="grade" placeholder="专业" value="{{\Auth::user()->grade}}" >
							</div>
                        <div class="col-md-6" style="padding-left:0px">
                        	<label>专业</label>
                            <input type="text" class="form-control" id="major" placeholder="专业" value="{{\Auth::user()->major}}" >
                        </div>
                </div>
				
	            <div class="btn btn-success pull-right" style="margin-top:10px" id="submit-info">保存
	            </div>
	        </div>
	        </div>
	    </section>
	</div>
 	 </div>
	
</div>
<script>
	var token = document.querySelector('#token').getAttribute('value');
	$("#submit-info").click(function(){
		 $.ajax({
             type: "post",
             url: "/user/profile",
             data: {grade:$('#grade').val(),major:$('#major').val(), _token:token},
             dataType: "json",
             success: function(data){
               
                if(data.state == 'success')
                {
                	$('#profile-name').html($('#name').val());
                	$('#dLabel').html($('#name').val());
                }
                else
                {
                	alert('更新失败');
                }
             }
         });
	});
	$("#submit-motto").click(function(){
		 $.ajax({
             type: "post",
             url: "/user/profile/",
             data: {motto:$('#motto').val(), _token:token},
             dataType: "json",
             success: function(data){
                if(data.state == 'success')
                {
                	$("#my-motto").html($('#motto').val());
                }
                else
                {
                	alert('更新失败');
                }
                $('#myModal').modal('toggle');
             }
         });
	});
</script>
@stop