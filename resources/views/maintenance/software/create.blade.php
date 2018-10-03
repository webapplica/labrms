@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-2 col-sm-8 col-md-offset-3 col-md-6 panel panel-body">
	<legend>
		<h3 class="text-muted">Software: Create</h3>
	</legend>
	
	<ol class="breadcrumb">
		<li><a href="{{ url('software') }}">Software</a></li>
		<li class="active">Create</li>
	</ol>

	@include('errors.alert')

	{{ Form::open(['method' => 'post', 'route' => 'software.store']) }}
		
		@include('maintenance.software.partials.form')

		<div class="form-group">
			<button type="submit" class="btn btn-lg btn-primary btn-block">Create</button>
		</div>
	{{ Form::close() }}
</div>
@stop