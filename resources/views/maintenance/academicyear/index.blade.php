@extends('layouts.app')

@section('content')
<div class="container panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Academic Year</h3>
	</legend>

	<table 
		class="table table-striped table-hover table-bordered" 
		id='academic-year-table'
		data-csrf-token="{{ csrf_token() }}"
		data-base-url="{{ url('academicyear') }}"
		data-create-url="{{ url('academicyear/create') }}">
		<thead>
			<th class="col-sm-1">ID</th>
			<th class="col-sm-1">Name</th>
			<th class="col-sm-2">Start</th>
			<th class="col-sm-1">End</th>
			<th class="no-sort col-sm-2"></th>
		</thead>
	</table>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#academic-year-table');
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
	            { data: "name" },
	            { data: "start" },
	            { data: "end" },
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
