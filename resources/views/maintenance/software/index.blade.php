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
			<th>ID</th>
			<th>Software name</th>
			<th>Company</th>
			<th>License type</th>
			<th>Software type</th>
			<th>Minimum System Requirements</th>
			<th>Recommended System Requirements</th>
			<th class="no-sort"></th>
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
					linkUrl = base_url + '/' + callback.id;

					return `
						<a href="` + linkUrl + `" class="btn btn-default btn-md">View</a>
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
