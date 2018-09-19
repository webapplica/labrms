@extends('layouts.master-blue')
@section('title')
Maintenance Activity
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ url('css/style.css') }}"  />
@stop
@section('content')
<div class="container-fluid" id="page-body">
@include('modal.maintenance.activity.create')
@include('modal.maintenance.activity.edit')
	<div class="col-md-12" id="workstation-info">
		<div class="panel panel-body   table-responsive">
			<legend><h3 class="text-muted">Maintenance Activities</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-striped table-bordered" id="maintenanceActivityTable">
				<thead>
					<th>ID</th>
					<th>Type</th>
					<th id="problem">Activity</th>
					<th id="problem">Details</th>
					<th class="no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">

	$(document).ready(function() {

	    var table = $('#maintenanceActivityTable').DataTable( {
			"pageLength": 100,
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			columnDefs: [
				{ targets: 'no-sort', orderable: false }
			],
			"processing": true,
	        ajax: "{{ url('maintenance/activity') }}",
	        columns: [
	            { data: "id" },
	            { data: "type" },
	            { data: "name" },
	            { data: "details" },
	            { data: function(callback){
	            	return `
 						<a class="btn btn-default edit" href="{{ url('maintenance/activity/') }}/` + callback.id + `/edit">
 							<span class="glyphicon glyphicon-pencil"></span>  Update
						</a>
 						<button class="btn btn-danger delete" data-id="` + callback.id + `">
 							<span class="glyphicon glyphicon-trash"></span> Remove
						</button>
 					`
	            } },
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="{{ url('maintenance/activity/create') }}" id="new" class="btn btn-primary btn-md">
 					<span class="glyphicon glyphicon-plus"> </span>  Create new Activity
 			</a>
		`);

		$('#maintenanceActivityTable').on('click', '.delete', function(){
			id = $(this).data('id')
	        swal({
	          title: "Are you sure?",
	          text: "This activity will be removed. Do you want to continue",
	          type: "warning",
	          showCancelButton: true,
	          confirmButtonText: "Yes, delete it!",
	          cancelButtonText: "No, cancel it!",
	          closeOnConfirm: false,
	          closeOnCancel: false
	        },
	        function(isConfirm){
	          if (isConfirm) {
					$.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
					type: 'delete',
					url: '{{ url("maintenance/activity") }}' + "/" + id,
					data: {
						'id': id
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Activity removed','success')
			        		table.ajax.reload()
			        	}else{
							swal('Operation Unsuccessful','Error occurred while deleting a record','error')
						}
					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while deleting a record','error')
					}
				});
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        })
	    });

  });
</script>
@stop
