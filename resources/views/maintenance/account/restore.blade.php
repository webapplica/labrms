w@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body  table-responsive">
	<legend>
		<h3 class="text-muted">Restore Accounts</h3>
	</legend>
	<ul class="breadcrumb">
		<li>{{ HTML::link('account','Account') }}</li>
		<li class='active'>Restore</li>
	</ul>
	<table id='users-table' class="table table-hover table-striped table-bordered table-condensed">
		<thead>
			<th>Lastname</th>
			<th>Firstname</th>
			<th>Middlename</th>
			<th>Email</th>
			<th>Mobile</th>
			<th>Type</th>
			<th>Action</th>
		</thead>
		<tbody>
		@forelse($user as $person)
			<tr>
				<td>{{ $person->lastname }}</td>
				<td>{{ $person->firstname }}</td>
				<td>{{ $person->middlename }}</td>
				<td>{{ $person->email }}</td>
				<td>{{ $person->contactnumber }}</td>
				<td>{{ $person->type }}</td>
				<td>
				{{ Form::open([ 'method' => 'delete', 'route' => array('account.restore', $person->id), 'id' => 'restoreForm' ]) }}
					<button class="btn btn-sm btn-info restore-btn pull-right btn-block" type="button">
						<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Restore
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

@section('scripts-include')
<script type='text/javascript'>
	$(document).ready(function () {
	    $('#users-table').DataTable();
		$('.restore-btn').click(function () {
			swal({
			  title: "Are you sure?",
			  text: "This account will be restored!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonColor: "#DD6B55",
			  confirmButtonText: "Yes, restore it!",
			  cancelButtonText: "No, cancel it!",
			  closeOnConfirm: false,
			  closeOnCancel: false
			},
			function (isConfirm) {
			  if (isConfirm) {
					$("#restoreForm").submit();
			  } else {
			    swal("Cancelled", "Restoration Cancelled", "error");
			  }
			});
		});
	} );
</script>
@stop
