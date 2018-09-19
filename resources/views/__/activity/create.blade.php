@extends('layouts.master-blue')
@section('title')
Maintenance Activity | Create
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/animate.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.min.css') }}" />
<style>
	#page-body{
		display:none;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class='col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6'>
		<div class="panel panel-body ">
	        <legend><h3 class="text-muted">Maintenance Activity Create</h3></legend>
			<ol class="breadcrumb">
			  <li><a href="{{ url('maintenance/activity') }}">Maintenance Activity</a></li>
			  <li class="active">Create</li>
			</ol>
			@include('errors.alert')
			{{ Form::open(['method'=>'post','route'=>'activity.store','class'=>'form-horizontal']) }}
				@include('maintenance.activity.form')
				<div class="form-group">
					<div class=" col-md-12">
						<button type="submit" class="btn btn-lg btn-primary btn-block">Create</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop
@section('script')
<script>
	$(document).ready(function(){
		
		$('input[type=radio]').on('change',function(){
			console.log($('#preventive').is(':checked'))
			if($('#preventive').is(':checked'))
			{
				$('#corrective-info').hide()
				$('#preventive-info').show().animateCSS('fadeIn')
			} else {

				$('#preventive-info').hide()
				$('#corrective-info').show().animateCSS('fadeIn')
			}
		})

	    $.fn.extend({
	        animateCSS: function (animationName) {
	            var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
	            this.addClass('animated ' + animationName).one(animationEnd, function() {
	                $(this).removeClass('animated ' + animationName);
	            });
	        }
	    });

		@if( Session::has("success-message") )
		  swal("Success!","{{ Session::pull('success-message') }}","success");
		@endif
		@if( Session::has("error-message") )
		  swal("Oops...","{{ Session::pull('error-message') }}","error");
		@endif

		$('#page-body').show();

	});
</script>
@stop
