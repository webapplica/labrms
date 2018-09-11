@extends('layouts.master-blue')
@section('title')
Event
@stop
@section('navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.navbar')
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="holiday-info">
		<div class="panel panel-body  table-responsive">
			<legend><h3 class="text-muted">Event</h3></legend>
			<p class="text-muted">Note: Other actions will be shown when a row has been selected</p>
			<table class="table table-hover table-striped table-bordered" id="eventTable">
				<thead>
					<th>ID</th>
					<th>Title</th>
					<th>Date</th>
					<th>Repeating</th>
					<th class="no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')
<script type="text/javascript">

	$(document).ready(function() {

    	var table = $('#eventTable').DataTable( {
			"pageLength": 50,
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
			"columnDefs": [
				{ targets: 'no-sort', orderable: false }
			],
	        ajax: "{{ url('event') }}",
	        columns: [
	        	{ data: 'id' },
	            { data: 'title' },
	            {
            		data: function(callback){
            			return moment(callback.date).format("dddd, MMMM Do YYYY");
            	} },
	            { data: function(callback){
	            	if(callback.repeating)
	            	{
	            		return callback.repeatingFormat;
	            	} else {
	            		return "";
	            	}
	            } },
	            { data: function(callback){
	            	return `

			 			<a class="btn btn-default" href="{{ url('event') }}/` + callback.id + `/edit">
			 				<span class="glyphicon glyphicon-pencil"></span>  Edit
			 			</a>
			 			<button type="button" class="btn btn-danger delete">
			 				<span class="glyphicon glyphicon-trash"></span> Remove
			 			</button>

		 			`
	            }}
	        ],
	    } );

	 	$("div.toolbar").html(`

	 			<a id="new" class="btn btn-primary" href="{{ url('event/create') }}">
	 				<span class="glyphicon glyphicon-plus"></span>  Create
	 			</a>
		`);

	    $('#delete').on('click',function(){	
	        swal({
	          title: "Are you sure?",
	          text: "Do you really want to delete this holiday?",
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
					url: '{{ url("event") }}' + "/" + table.row('.selected').data().id,
					data: {
						'selected': $('#selected').val()
					},
					dataType: 'json',
					success: function(response){
						if(response == 'success'){
							swal('Operation Successful','Holiday removed','success')
			        	}else{
							swal('Operation Unsuccessful','Error occurred while processing your request','error')
						}

						table.ajax.reload();
					},
					error: function(){
						swal('Operation Unsuccessful','Error occurred while processing your request','error')
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
