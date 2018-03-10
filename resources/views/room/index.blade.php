@extends('layouts.master-blue')

@section('content')
@include('modal.room.create')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="room-info">
		<div class="panel panel-body table-responsive">
			<legend><h3 class="text-muted">Laboratory Room</h3></legend>

			<table class="table table-hover table-condensed table-striped table-bordered" id="roomTable">
				<thead>
					<th class=>ID</th>
					<th class=>Name</th>
					<th class=>Description</th>
					<th class="no-sort"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#roomTable').DataTable( {
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
	            { data: function(callback){
	            	return `
	            		<a href="` + '{{ url('room') }}' + '/' + callback.id +`" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-th-list"></span> View</a>
			 			<a href="{{ url('room') }}/`+callback.id+`/edit" class="btn btn-warning btn-sm"><span class="glyphicon glyphicon-pencil"></span>  Update</a>
			 			<button id="delete" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Remove</button>
	            	`;
	            } }
	        ],
	    } );

	    $('div.toolbar').html(`
			<a href="{{ url('room/create') }}" class="btn btn-primary btn-sm">Create</a>
    	`)

	    $('#roomTable').on('click', 'delete', function(){
			try{
				if(table.row('.selected').data().id != null && table.row('.selected').data().id  && table.row('.selected').data().id >= 0)
				{
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
							url: '{{ url("room/") }}' + "/" + table.row('.selected').data().id,
							data: {
								'id': table.row('.selected').data().id
							},
							dataType: 'json',
							success: function(response){
								if(response == 'success'){
									swal('Operation Successful','Room removed from database','success')
					        		table.row('.selected').remove().draw( false );
					        	}else{
									swal('Operation Unsuccessful','Error occurred while deleting a record','error')
								}
					            $('#edit').hide()
					            $('#delete').hide()
							},
							error: function(){
								swal('Operation Unsuccessful','Error occurred while deleting a record','error')
							}
						});
			          } else {
			            swal("Cancelled", "Operation Cancelled", "error");
			          }
			        })
				}
			}catch( error ){
				swal('Oops..','You must choose atleast 1 row','error');
			}
	    });
	} );
</script>
@stop
