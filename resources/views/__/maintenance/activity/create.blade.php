@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body">
    <legend><h3 class="text-muted">Maintenance Activity: Create</h3></legend>

	<ol class="breadcrumb">
	  <li><a href="{{ url('maintenance/activity') }}">Maintenance Activity</a></li>
	  <li class="active">Create</li>
	</ol>

	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'route' => 'activity.store']) }}
		@include('maintenance.activity.partials.form')
		<div class="form-group">
			<div class=" col-md-12">
				<button type="submit" class="btn btn-lg btn-primary btn-block">Create</button>
			</div>
		</div>
	{{ Form::close() }}

</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		$('input[type=radio]').on('change',function () {
			if($('#preventive').is(':checked')) {
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
	});
</script>
@stop
