@extends('layouts.app')

@section('content')
	<div class="container-fluid">
		<div class=" panel panel-body table-responsive" style="padding: 20px;">
			<legend><h3 class="text-muted">Accounts</h3></legend>
			<ul class="breadcrumb">
				<li class="">
					<a href="{{ url('/') }}">Home</a>
				</li>
				<li class="active">Account</li>
			</ul>
			<input type="hidden" name="_url" id="_url" value="{{ url('account') }}" />
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
		var baseUrl = $('#_url').val();
	  	var table = $('#users-table').DataTable({
			'serverSide': true,
			"processing": true,
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
			ajax: baseUrl,
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
		 			<a href="` + baseUrl + callback.id + `/edit" class="btn btn-default">
		 				Update Info
					</a>
		 			<button type="button" id="access" class="btn btn-success">
		 				Set Access
					</button>
		 			<button type="button" class="activation-btn btn btn-warning">
		 				Activation
					</button>
		 			<button type="button" class="password-reset-btn btn btn-info">
		 				Reset Password
					</button>
		 			<button type="button" class="remove-btn btn btn-danger" data-id='` + callback.id + `'>
		 				Remove
					</button>
				`;
			  }}
			],
    	});

	 	$("div.toolbar").html(`
 			<a href="` + baseUrl + `/create" id="new" class="btn btn-primary">
 				Add New Account
			</a>
		`);

	    $('#users-table').on('click', '.password-reset-btn', function () {
	    	id = $(this).data('id');
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
						url: baseUrl + '/password/reset',
						data: {
							'id': id
						},
						dataType: 'json',
						success: function(response){
							swal('Operation Successful', response.message ,'success')
						},
						error: function(response){
							swal('Operation Unsuccessful', response.message ,'error')
						},
						complete: function () {
							table.ajax.reload()
						}
					});
				} else {
					swal("Cancelled", "Operation Cancelled", "error");
				}
			});
	    });

	    $('#users-table').on('click', '.remove-btn', function () {
	    	id = $(this).data('id');
	        swal({
	          title: "Are you sure?",
	          text: "Account will be removed. Do you want to continue?",
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
					url: baseUrl + '/' + id,
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						swal('Success', response.message ,'success')
					},
					error: function(response){
						swal('Oopss!', response.message ,'error')
					},
					complete: function () {
						table.ajax.reload()
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
