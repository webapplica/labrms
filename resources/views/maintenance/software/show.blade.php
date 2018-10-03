@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-default panel-body">
	<legend>
		<h3 class="text-muted">Software: {{ $software->name }}</h3>
	</legend>

	<ol class="breadcrumb">
		<li><a href="{{ url('software') }}">Software</a></li>
		<li class="active">{{ $software->name }}</li>
	</ol>
	<div class="panel panel-info" style="border: none; border-radius: 0;">
		<div class="panel-heading">
			<h4>Basic Information</h4>
		</div>
		<div class="list-group">
			<a href="#" class="list-group-item">
				<strong>Name:</strong> 
				{{ $software->name }}
			</a>

			<a href="#" class="list-group-item">
				<strong>Company:</strong> 
				{{ $software->company }}
			</a>

			<a href="#" class="list-group-item">
				<strong>License Type:</strong> 
				{{ $software->license_type }}
			</a>

			<a href="#" class="list-group-item">
				<strong>Software Type:</strong> 
				{{ $software->software_type }}
			</a>

			<a href="#" class="list-group-item">
				<strong>Minimum System Requirements:</strong> 
				{{ $software->minimum_requirements }}
			</a>

			<a href="#" class="list-group-item">
				<strong>Recommended System Requirements:</strong> 
				{{ $software->recommended_requirements }}
			</a>
		</div>
	</div>

	<div class="panel panel-default col-sm-6">
		<!-- Default panel contents -->
		<div class="panel-heading">
			Software Licenses
			<a href="{{ url('software/' . $software->id . '/' . 'license/create') }}" class="btn btn-success btn-sm pull-right">Add New</a>
			<div class="clearfix"></div>
		</div>

		<!-- Table -->
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>Code</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="col-md-2">1</td>
					<td class="col-md-3">XSCD-S3S4-G943-SL2S</td>
					<td class="col-md-1">
						<form method="post" action="{{ url('software/license') }}">
						
							<button type="submit" class="btn btn-sm btn-danger">Remove</button>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="panel panel-default col-sm-offset-1 col-sm-5">
		<!-- Default panel contents -->
		<div class="panel-heading">
			Room Assignment
			<a href="{{ url('software/' . $software->id . '/' . 'room/assign') }}" class="btn btn-success btn-sm pull-right">Attach to a room</a>
			<div class="clearfix"></div>
		</div>

		<!-- Table -->
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="col-md-2">ID</th>
					<th class="col-md-3">Name</th>
					<th class="col-md-1"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>S501</td>
					<td>
						<form method="post" action="{{ url('software/license') }}">
						
							<button type="submit" class="btn btn-sm btn-danger">Remove</button>
						</form>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@stop