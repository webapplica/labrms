@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-default panel-body">
	<legend>
		<h3 class="text-muted">Room: {{ $room->name }}</h3>
	</legend>

	<ul class="breadcrumb">
		<li><a href="{{ url('room') }}">Room</a></li>
		<li class="active">{{ $room->name }}</li>
	</ul>

	<ul class="list-unstyled">
		<div class="row">
			<div class="col-sm-6">
				<li><h5 class="text-muted"><label>Name:</label> {{ $room->name }} </h5></li>
				<li><h5 class="text-muted"><label>Category:</label> {{ implode( ',', $room->categories->pluck('name')->toArray() ) }} </h5></li>
				<li><h5 class="text-muted"><label>Description:</label> {{ $room->description }} </h5></li>
			</div>
			<div class="col-sm-6">
				<li><h5 class="text-muted"><label>Inventory List</label></h5></li>
				<ul></ul>
			</div>
		</div>
	</ul>

	<table class="table table-bordered" id="room-history-table">
		<thead>
			<tr>
	            <th class="text-center" colspan=5>Tickets</th>
			</tr>
			<tr>
	            <th>ID</th>
	            <th>Name</th>
	            <th>Details</th>
	            <th>Author</th>
	            <th>Status</th>
			</tr>
        </thead>
	</table>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
$(document).ready(function () {
	var historyTable = $('#room-history-table').DataTable( {
	    language: {
	        searchPlaceholder: "Search..."
	    },
	    order: [[ 0, "desc" ]],
		"processing": true,
        ajax: "{{ url("room/$room->id/history") }}",
        columns: [
        	{ data: 'id' },
        	{ data: 'name' },
        	{ data: 'details' },
        	{ data: 'author' },
        	{ data: 'status' }
        ],
    } );
})
</script>
@stop
