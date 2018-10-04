@extends('layouts.app')

@section('content')
<div class="container-fluid col-sm-offset-3 col-sm-6 panel panel-default panel-body">
	<legend>
		<h3 class="text-muted">Software: {{ $software->name }}</h3>
	</legend>

	@include('errors.alert')

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

	<div class="panel panel-default col-sm-6" style="border: none; border-radius: 0;">
		<!-- Default panel contents -->
		<div class="panel-heading">
			Software Licenses
		</div>

		<div class="panel-body">
			<p>You can add new software license by inputting the license on the field below and submit it by clicking the button beside it.</p>
			
			<form method="post" action="{{ url('software/' . $software->id . '/license') }}" id="add-license-form">
				<div class="input-group">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<input 
							type="text" 
							name="license" 
							class="form-control" 
							aria-describedby="input-group-add-license"
							placeholder="Enter license code here..." />
						<span 
							role="button" 
							type="submit" 
							class="input-group-addon btn-success" 
							id="input-group-add-license" 
							onclick="$(this).closest('form').submit()">
							{{ __('Add') }}
						</span>
				</div>
			</form>
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
				@forelse($licenses as $license)
				<tr>
					<td class="col-md-2">{{ $license->id }}</td>
					<td class="col-md-3">{{ $license->key }}</td>
					<td class="col-md-1">
						<form method="post" action="{{ url('software/' . $license->id . '/license') }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<input type="hidden" name="_method" value="DELETE" />
							<button type="submit" class="btn btn-sm btn-danger">Remove</button>
						</form>
					</td>
				</tr>
				@empty
				<tr>
					<td class="col-md-2 text-center" colspan=4>No record found</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
	<div class="panel panel-default col-sm-6" style="border: none; border-radius: 0;">
		<!-- Default panel contents -->
		<div class="panel-heading">
			Room Assignment
		</div>

		<div class="panel-body">
			<p>You can assign new room to this software by selecting the room you want to link and click the add button beside it</p>
			
			<form method="post" action="{{ url('software/' . $software->id . '/assign/room') }}" id="assign-room-form">
				<div class="input-group">
						<input type="hidden" name="_token" value="{{ csrf_token() }}" />
						<select
							name="room"
							class="form-control">

							@foreach($rooms as $room)
								<option value="{{ $room->id }}">
									{{ $room->name }}
								</option>
							@endforeach

						</select>
						<span 
							role="button" 
							type="submit" 
							class="input-group-addon btn-success" 
							id="input-group-add-license" 
							onclick="$(this).closest('form').submit()">
							{{ __('Assign') }}
						</span>
				</div>
			</form>
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
				@forelse($room_assignments as $room)
				<tr>
					<td class="col-md-2">{{ $room->id }}</td>
					<td class="col-md-3">{{ $room->name }}</td>
					<td class="col-md-1">
						<form method="post" action="{{ url('software/' . $software->id . '/unassign/room/' . $room->id) }}">
							<input type="hidden" name="_token" value="{{ csrf_token() }}" />
							<input type="hidden" name="_method" value="DELETE" />
							<button type="submit" class="btn btn-sm btn-danger">Remove</button>
						</form>
					</td>
				</tr>
				@empty
				<tr>
					<td class="col-md-2 text-center" colspan=4>No record found</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
@stop