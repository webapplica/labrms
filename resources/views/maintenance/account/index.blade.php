@extends('layouts.app')

@section('body-content')
	<div class="container-fluid">
		<div class=" panel panel-body table-responsive" style="padding: 20px;">
			<legend><h3 class="text-muted">Accounts</h3></legend>
			<ul class="breadcrumb">
				<li class="">
					<a href="{{ url('/') }}">Home</a>
				</li>
				<li class="active">Account</li>
			</ul>
			<table id='users-table' class="table table-hover table-striped" width="100%">
				<thead>
					<th>ID</th>
					<th>Username</th>
					<th>Lastname</th>
					<th>Firstname</th>
					<th>Middlename</th>
					<th>Email</th>
					<th>Mobile</th>
					<th>Type</th>
					<th>Privilege</th>
					<th>Status</th>
					<th class="no-sort"></th>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>
@stop
@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function() {
	  	var table = $('#users-table').DataTable({
			"pageLength": 100,
			'serverSide': true,
	  		select: {
	  			style: 'single'
	  		},
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-2'l><'col-sm-7'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
			ajax: "{{ url('account') }}",
			columns: [
			  { data: "id" },
			  { data: "username" },
			  { data: "lastname" },
			  { data: "firstname" },
			  { data: "middlename" },
			  { data: "email" },
			  { data: "contactnumber" },
			  { data: "type" },
			  { data: "access_type"},
			  { data: "status_name" },
			  { data: function(callback) {
			  	return `
		 			<a href="{{ url('account') }}/` + callback.id + `/edit" class="btn btn-default">
		 				Update Info
					</a>
		 			<button id="access" class="btn btn-success">
		 				Set Access
					</button>
		 			<button class="activation-btn btn btn-warning">
		 				Activation
					</button>
		 			<button class="password-reset-btn btn btn-info">
		 				Reset Password
					</button>
		 			<button class="remove-btn btn btn-danger" data-id='` + callback.id + `'>
		 				Remove
					</button>
				`;
			  }}
			],
    	});

	 	$("div.toolbar").html(`
 			<a href="{{ url('account/create') }}" id="new" class="btn btn-primary">
 				Add New Account
			</a>
		`);

	    $('#users-table').on('click', '.password-reset-btn', function () {
			swal({
				title: "Are you sure?",
				text: "This will reset this accounts password to the default '12345678'?",
				type: "warning",
				showCancelButton: true,
				confirmButtonText: "Yes, reset it!",
				cancelButtonText: "No, cancel it!",
				closeOnConfirm: false,
				closeOnCancel: false
				},
				function (isConfirm) {
					if (isConfirm) {
						$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						type: 'post',
						url: '{{ url("account/password/reset") }}',
						data: {
							'id': table.row('.selected').data().id
						},
						dataType: 'json',
						success: function(response){
							swal('Operation Successful', response.message ,'success')
						},
						error: function(response){
							swal('Operation Unsuccessful', response.message ,'error')
						}
					});
				} else {
					swal("Cancelled", "Operation Cancelled", "error");
				}
			});
	    });

	    $('#users-table').on('click', 'remove-btn', function () {
	        swal({
	          title: "Are you sure?",
	          text: "Account will be removed from database?",
	          type: "warning",
	          showCancelButton: true,
	          confirmButtonText: "Yes, delete it!",
	          cancelButtonText: "No, cancel it!",
	          closeOnConfirm: false,
	          closeOnCancel: false
	        },
	        function (isConfirm) {
	          if (isConfirm) {
					$.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
					type: 'delete',
					url: '{{ url("account/") }}' + "/" + table.row('.selected').data().id,
					data: {
						'id': table.row('.selected').data().id
					},
					dataType: 'json',
					success: function(response){
						swal('Operation Successful', response.message ,'success')
					},
					error: function(response){
						swal('Operation Unsuccessful', response.message ,'error')
					}
				});
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
	    });
	} );
</script>
@stop
