@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Faculty</h3>
	</legend>

	<table 
		class="table table-striped table-hover table-bordered" 
		id='faculty-table'
		data-csrf-token="{{ csrf_token() }}"
		data-base-url="{{ url('faculty') }}"
		data-create-url="{{ url('faculty/create') }}">
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
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#faculty-table');
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		var base_url = table.data('base-url');
		var create_url = table.data('create-url');

	    var dataTable = table.DataTable( {
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
	        ajax: base_url,
	        columns: [
	            { data: "id" },
	            { data: "title" },
	            { data: "firstname" },
	            { data: "middlename" },
	            { data: "lastname" },
	            { data: "suffix" },
	            { data: "contactnumber" },
	            { data: "email" },
	            { data: function (callback) {
	            	return `
						<form method="post" action="` + base_url + '/' + callback.id + `">

							<input type="hidden" name="_token" value="` + csrf_token + `" />
							<input type="hidden" name="_method" value="delete" />

							<a href="` + base_url + '/' + callback.id + `/edit" class="btn btn-default">
								Update
							</a>

							<button type="submit" data-id="` + callback.id + `" class="remove-btn btn btn-danger">
								Remove
							</button>

						</form>
	            	`;
	            } }
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="` + create_url + `" id="new" class="btn btn-primary">
 				<span class="glyphicon glyphicon-plus"></span>  Add
 			</a>
		`);
	})
</script>	
@stop
