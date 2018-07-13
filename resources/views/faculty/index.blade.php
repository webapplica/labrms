@extends('layouts.master-blue')

@section('style')
{{ HTML::style(asset('css/select.bootstrap.min.css')) }}
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<style>
	#page-body,#edit,#delete{
		display: none;
	}

	.panel {
		padding: 30px;
	}
</style>
@stop
@section('content')
<div class="container-fluid" id="page-body">
	<div class="col-md-12" id="faculty-info">
		<div class="panel panel-body table-responsive">
			<legend>Faculty</legend>
			<table class="table table-striped table-hover table-bordered" id='facultyTable'>
				<thead>
					<th class="col-sm-1">ID</th>
					<th class="col-sm-1">Title</th>
					<th class="col-sm-1">First Name</th>
					<th class="col-sm-1">Middle Name</th>
					<th class="col-sm-1">Last Name</th>
					<th class="col-sm-1">Suffix</th>
					<th class="col-sm-1">Contact Number</th>
					<th class="col-sm-1">Email Address</th>
					<th class="no-sort col-sm-1"></th>
				</thead>
			</table>
		</div>
	</div>
</div>
@stop
@section('script')

<script>
	$(document).ready(function(){

	    var table = $('#facultyTable').DataTable( {
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
			"processing": true,
	        ajax: "{{ url('faculty') }}",
	        columns: [
	            { data: "id" },
	            { data: "title" },
	            { data: "firstname" },
	            { data: "middlename" },
	            { data: "lastname" },
	            { data: "suffix" },
	            { data: "contactnumber" },
	            { data: "email" },
	            { data: function(callback){
	            	return `
	            			<a href="{{ url("faculty") }}` + '/' + callback.id + '/edit' + `" class="btn btn-sm btn-default">Edit</a>
	            			<button type="button" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Removing Faculty" data-id="`+callback.id+`" class="remove btn btn-sm btn-danger">Remove</button>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="{{ url('faculty/create') }}" id="new" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>  Add
 			</a>
		`);

		$('#facultyTable').on('click','button.remove',function(){	
		  	var removeButton = $(this);
			removeButton.button('loading');
			$.ajax({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
				type: 'delete',
				url: '{{ url("faculty") }}' + '/' + $(this).data('id'),
				dataType: 'json',
				success: function(response){
					if(response == 'success')
						swal("Operation Success",'Faculty removed.',"success")
					else
						swal("Error Occurred",'An error has occurred while processing your data.',"error")
					table.ajax.reload()
			  		removeButton.button('reset');
				},
				error: function(response){
					swal("Error Occurred",'An error has occurred while processing your data.',"error")
				}

			})
		})
	})
</script>	
@stop
