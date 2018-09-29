@extends('layouts.app')

@section('content')
<div class="container-fluid col-md-12 panel panel-body table-responsive">
	<legend><h3 class="text-muted">Maintenance Activities</h3></legend>
	<table 
		class="table table-hover table-striped table-bordered" 
		id="maintenance-activity-table"
		data-ajax-url="{{ url('maintenance/activity') }}"
		data-base-url="{{ url('maintenance/activity') }}"
		data-create-url="{{ url('maintenance/activity/create') }}"
		data-csrf="{{ csrf_token() }}">
		<thead>
			<th>ID</th>
			<th>Type</th>
			<th id="problem">Activity</th>
			<th id="problem">Details</th>
			<th class="no-sort"></th>
		</thead>
	</table>
</div>
@stop

@section('scripts-include')
{{ HTML::script(asset('js/dataTables.select.min.js')) }}
{{ HTML::script(asset('js/moment.min.js')) }}
<script type="text/javascript">
	$(document).ready(function () {
		var table = $('#maintenance-activity-table');
		var ajax_url = table.data('ajax-url');
		var create_url = table.data('create-url');
		var base_url = table.data('base-url');
		var csrf = table.data('csrf');
		
	    var dataTable = dataTable.DataTable( {
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
	        ajax: ajax_url,
	        columns: [
	            { data: "id" },
	            { data: "type" },
	            { data: "name" },
	            { data: "details" },
	            { data: function(callback){
	            	var remove_url = base_url + '/' + callback.id;
	            	var edit_url = base_url + `/` + callback.id;
	            	return ` 
	            		<form method="post" action="` + remove_url + `">
	            			<input type="hidden" name="_token" value="` + csrf + `" />
	            			<input type="hidden" name="method" value="delete" />
	 						<a class="btn btn-default edit" href="` + edit_url + `/edit">
	 							Update
							</a>
	 						<button type="submit" class="btn btn-danger">
	 							Remove
							</button>
						</form>
 					`
	            } },
	        ],
	    } );

	 	$("div.toolbar").html(`
 			<a href="` + create_url + `" id="new" class="btn btn-primary">
 					Create New Activity
 			</a>
		`);
  });
</script>
@stop
