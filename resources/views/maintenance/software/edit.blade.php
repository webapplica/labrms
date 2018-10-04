@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body ">
	<legend>
		<h3 class="text-muted">Software {{ $software->name }}: Update</h3>
	</legend>
	
	<ol class="breadcrumb">
		<li><a href="{{ url('software') }}">Software</a></li>
		<li class="active">{{ $software->name }}</li>
		<li class="active">Edit</li>
	</ol>

	@include('errors.alert')

	{{ Form::open(['method' => 'put', 'route' => array('software.update', $software->id)]) }}

		@include('maintenance.software.partials.form')

		<div class="form-group">
			<button type="submit" class="btn btn-md btn-primary btn-block">Update</button>
		</div>
	{{ Form::close() }}
</div>
@stop
@section('script')
<script>
	$(document).ready(function(){

		$('#name').val('{{ $software->name }}')
		$('#company').val('{{ $software->company }}')
		$('#minreq').val('{{ $software->minimum_requirements }}')
		$('#maxreq').val('{{ $software->recommended_requirements }}')

		$('#next').click(function(){
			$('#page-one').hide(400);
			$('#page-two').show(400);
		});

		$('#previous').click(function(){
			$('#page-two').hide(400);
			$('#page-one').show(400);
		});

		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
			url: '{{ url("get/software/license/all") }}',
			dataType: 'json',
			success: function(response){
				options = "";
				for(ctr = 0;ctr<response.length;ctr++){
					options +=  `<option value="`+response[ctr]+`">`+response[ctr]+`</option>`;
				}
				$('#licensetype').html("");
				$('#licensetype').append(options);
				$('#licensetype').val('{{ $software->license_type }}')
			}
		});

		$.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'get',
			url: '{{ url("software/type") }}',
			dataType: 'json',
			success: function(response){
				options = "";
				for(ctr = 0;ctr<response.data.length;ctr++){
					options +=  `<option value="`+response.data[ctr].type+`">`+response.data[ctr].type+`</option>`;
				}
				$('#softwaretype').html("");
				$('#softwaretype').append(options);
				$('#softwaretype').val('{{ $software->type }}');
			}
		});

		$('#page-body').show();

	});
</script>
@stop
