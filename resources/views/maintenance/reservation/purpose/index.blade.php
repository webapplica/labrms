@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body  table-responsive">

	<legend><h3 class="text-muted">Reservation Purposes</h3></legend>

	<table 
		class="table table-striped table-bordered" 
		id='item-type-table'
		data-csrf-token="{{ csrf_token() }}"
		data-base-url="{{ url('purpose') }}"
		data-create-url="{{ url('purpose/create') }}">

		<thead>
			<th class="col-md-1">ID</th>
			<th class="col-md-4">Title</th>
			<th class="col-md-5">Description</th>
			<th></th>
		</thead>
	</table>

</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function () {

		var table = $('#item-type-table');
		var csrf_token = $('meta[name="csrf-token"]').attr('content');
		var base_url = table.data('base-url');
		var create_url = table.data('create-url');

    	var dataTable = table.DataTable( {
			"pageLength": 100,
	  		select: {
	  			style: 'single'
	  		},
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
	            { data: "description" },
	            { data: function(callback){
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
 				Add New Purpose
			</a>
		`);

	});
</script>
@stop
