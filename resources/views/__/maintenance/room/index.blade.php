@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-12 panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Laboratory Room</h3>
	</legend>

	<table class="table table-hover table-striped table-bordered" id="room-table">
		<thead>
			<th class=>ID</th>
			<th class=>Name</th>
			<th class=>Description</th>
			<th class="no-sort"></th>
		</thead>
	</table>
</div>
@endsection

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#room-table').DataTable({
			serverSide: true,
			processing: true,
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('room') }}",
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: "description" },
	            { data: function (callback) {
	            	return `
	            		<a href="` + '{{ url('room') }}' + '/' + callback.id +`" class="btn btn-default btn-sm">
	            			<span class="glyphicon glyphicon-th-list"></span> View
            			</a>
			 			<a href="{{ url('room') }}/`+callback.id+`/edit" class="btn btn-warning btn-sm">
			 				<span class="glyphicon glyphicon-pencil"></span>  Update
		 				</a>
			 			<button data-id="`+callback.id+`" class="remove-btn btn btn-danger btn-sm">
			 				<span class="glyphicon glyphicon-trash"></span> Remove
		 				</button>
	            	`;
	            } }
	        ],
	    } );

	    $('div.toolbar').html(`
			<a href="{{ url('room/create') }}" class="btn btn-primary btn-sm">Create</a>
    	`)

	    $('#room-table').on('click', '.remove-btn', function () {
			id = $(this).data('id');
	        swal({
	          title: "Are you sure?",
	          text: "This room will be removed from database?",
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
					url: '{{ url('room') }}' + '/' + id,
					dataType: 'json',
					success: function (response) {
						swal('Operation Successful', response.message,'success')
					},
					error: function (response) {
						swal('Operation Unsuccessful', response.message, 'error')
					},
					complete: function () {
						table.ajax.reload();
					}
				});
	          } else {
	            swal("Cancelled", "Operation Cancelled", "error");
	          }
	        });
	    });
	} );
</script>
@stop
