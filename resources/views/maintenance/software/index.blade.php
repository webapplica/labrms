@extends('layouts.app')

@section('content')
<div class="container-fluid panel panel-body table-responsive">
	<legend>
		<h3 class="text-muted">Software</h3>
	</legend>
	
	<table 
		class="table table-hover table-striped table-bordered" 
		id='software-table'
		data-base-url="{{ url('software') }}"
		data-create-url="{{ url('software/create') }}">
		<thead>
			<th class="col-md-1">ID</th>
			<th class="col-md-1">Software name</th>
			<th class="col-md-1">Company</th>
			<th class="col-md-1">License type</th>
			<th class="col-md-1">Software type</th>
			<th class="col-md-2">Minimum System Requirements</th>
			<th class="col-md-2">Recommended System Requirements</th>
			<th class="col-md-2 no-sort"></th>
		</thead>
	</table>
</div>
@stop

@section('scripts-include')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#software-table');
		var base_url = table.data('base-url');
		var create_url = table.data('create-url');
		var csrf_token = $('meta[name="csrf-token"]').attr('content');

	    var dataTable = table.DataTable( {
	    	serverSide: true,
			processing: true,
	    	columnDefs:[
				{ targets: 'no-sort', orderable: false },
	    	],
		    language: {
		        searchPlaceholder: "Search..."
		    },
	    	"dom": "<'row'<'col-sm-3'l><'col-sm-6'<'toolbar'>><'col-sm-3'f>>" +
						    "<'row'<'col-sm-12'tr>>" +
						    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
	        ajax: base_url,
	        columns: [
	            { data: "id" },
	            { data: "name" },
	            { data: "company" },
	            { data: "license_type" },
	            { data: "type" },
	            { data: "minimum_requirements" },
	            { data: "recommended_requirements" },
	            { data: function(callback) {
					viewLink = base_url + '/' + callback.id;
					editLink = base_url + '/' + callback.id + '/edit';

					return `
						<form method="post" action="` + viewLink + `">
							<input type="hidden" name="_method" value="DELETE" />
							<input type="hidden" name="_token" value="` + csrf_token + `" />
							<a href="` + viewLink + `" class="btn btn-default btn-md">View</a>
							<a href="` + editLink + `" class="btn btn-info btn-md">Update</a>
							<button type="submit" class="btn btn-danger btn-md">Update</button>
						</form>
					`;
	          	} }
	        ],
	    } );

		$('div.toolbar').append(
			$('<a />', {
				href: create_url,
				class: 'btn btn-primary',
				text: ' Create',
			}).prepend( $('<span />', { class: 'glyphicon glyphicon-plus' }) ), 
		)

	});
</script>
@stop
