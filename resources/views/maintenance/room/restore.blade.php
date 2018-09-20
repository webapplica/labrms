@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body table-responsive">
	<legend>
		<h3>Room: Restore</h3>
	</legend>

    <ul class="breadcrumb">
		<li>{{ HTML::link('room','Room') }}</li>
		<li class="active">Restore</li>
	</ul>

	<table class="table table-condensed table-hover table-striped table-bordered" id="roomTable">
		<thead>
			<th>Name</th>
			<th>Description</th>
			<th>Action</th>
		</thead>
		<tbody>
		@forelse($rooms as $room)
		<tr>
			<td class="col-md-5" >{{ $room->name }}</td>
			<td class="col-md-5" >{{ $room->description }}</td>
			<td class="col-md-2">
				{{ Form::open(['method'=>'put', 'route' => array('room.restore', $room->id), 'id' => 'restore-table']) }}
				<button 
					class="btn btn-md btn-block btn-default delete" 
					name="delete" 
					type="button" 
					value="Condemn">
					Restore
				</button>
				{{ Form::close() }}
			</td>
		</tr>
		@empty
		@endforelse
		</tbody>
	</table>
</div>
@stop
@section('script')
<script type="text/javascript">
	$(document).ready(function () {
		$('#restore-table').DataTable();

		$('.delete').click(function () {
			swal({
				title: "Are you sure?",
				text: "Do you really want to restore this room?",
				type: "warning",
				showCancelButton: true,
				confirmButtonText: "Yes, i want to continue!",
				cancelButtonText: "No, cancel it!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm){
				if (isConfirm) {
					$("#restore-table").submit();
				} else {
					swal("Ooops!!", "Operation Cancelled", "error");
				}
			});
		});
	} );
</script>
@stop
