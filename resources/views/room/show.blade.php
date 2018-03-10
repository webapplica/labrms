@extends('layouts.master-blue')

@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-sm-12">
		<div class="panel panel-default" style="padding:0px 20px">
			<div class="panel-body">
				<legend><h3 class="text-muted">Room {{ $room->name }}</h3></legend>
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
							<ul>

							</ul>
						</div>
					</div>
				</ul>
				<hr />
		    	<div class="panel panel-body" style="padding: 10px;">
					<table class="table table-bordered" id="historyTable">
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
			</div>
		</div>
	</div>
</div>
@stop
@section('script')

<script type="text/javascript">
$(document).ready(function(){

	var historyTable = $('#historyTable').DataTable( {
	    language: {
	        searchPlaceholder: "Search..."
	    },
	    order: [[ 0, "desc" ]],
		"processing": true,
        ajax: "{{ url("ticket/room/$room->id") }}",
        columns: [
        	{ data: 'id' },
        	{ data: 'ticketname' },
        	{ data: 'details' },
        	{ data: 'author' },
        	{ data: 'status' }
        ],
    } );
})
</script>
@stop
