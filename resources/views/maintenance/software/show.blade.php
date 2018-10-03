@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-12 panel panel-default panel-body">
	<legend>
		<h3 class="text-muted">Software: {{ $software->name }}</h3>
	</legend>

	<ol class="breadcrumb">
		<li><a href="{{ url('software') }}">Software</a></li>
		<li class="active">{{ $software->name }}</li>
	</ol>
</div>
@stop